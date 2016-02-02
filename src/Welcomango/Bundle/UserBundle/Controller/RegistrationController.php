<?php

namespace Welcomango\Bundle\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\RegistrationController as BaseProfileController;

use Welcomango\Model\City;
use Welcomango\Model\User;
use Welcomango\Model\Experience;

/**
 * Class RegistrationController
 */
class RegistrationController extends BaseProfileController
{
    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $this->getUser();
        if (is_object($user) || $user instanceof UserInterface) {
            throw new AccessDeniedException('Why do you want to register again?');
        }

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            //THIS IS WHY WE OVERRIDE THIS FUNCTION
            //GET CITIES AND COUNTRIES AND ADD IT TO DATABASE IF WE DON'T ALREADY HAVE IT
            $cityManager = $this->get('welcomango.front.city.manager');
            $fromCity = $cityManager->checkAndCreateNewCity($form->get('fromCity'));
            $currentCity = $cityManager->checkAndCreateNewCity($form->get('currentCity'));

            $user->setCurrentCity($currentCity);
            $user->setFromCity($fromCity);

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/register/check-email", name="user_registration_check_email")
     * @Template()
     *
     * @return String
     */
    public function confirmEmailAction(Request $request)
    {
        return array();
    }

    /**
     * @param Request $request
     *
     * @Route("/register/email/confirmed", name="user_registration_email_confirmed")
     * @Template()
     *
     * @return String
     */
    public function confirmedEmailAction(Request $request)
    {
        return array();
    }

    /**
     * Check User Form filed for Ajax Calls
     *
     * @param Request $request
     *
     * @Route("/json/registration/form/list.json", name="user_registration_check_ajax", defaults={"_format"="json"})
     *
     * @return String
     */
    public function checkFormAction(Request $request)
    {
        $response              = array();
        $response['message']   = 'Oops. Something went wrong.';
        $response['container'] = 'warning';

        $userRepository = $this->getDoctrine()->getManager()->getRepository('Welcomango\Model\User');
        if ($request->request->has('query') && $request->request->get('query') != '' && $request->request->has('field') && $request->request->get('field') != '') {
            $query = $request->request->get('query');
            $field = $request->request->get('field');
            $field = \str_replace('fos_user_registration_form_', '', $field);
            $result = $userRepository->findBy(array($field => $query));

            if ($result) {
                $response['message'] = $this->trans($field.'taken', [], 'interface');
                $response['class']   = 'alert alert-danger';
            } else {
                $response['message'] = $this->trans($field.'.free', [], 'interface');
                $response['class']   = 'alert alert-success';
            }
        }

        return new JsonResponse($response);
    }
}
