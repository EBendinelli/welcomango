<?php

namespace Welcomango\Bundle\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Controller\RegistrationController as BaseProfileController;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Welcomango\Model\Experience;
use Welcomango\Model\City;
use Welcomango\Model\User;

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

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }


        $form = $this->createForm($this->get('welcomango.front.form.user.type'), $user);
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
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
     * Check User Form filed for Ajax Calls
     *
     * @param Request $request
     *
     * @Route("/json/registration/form/list.json", name="user_registration_check_ajax", defaults={"_format"="json"})
     *
     * @return String
     */
    public function checkFormAction(Request $request){
        $response = array();
        $response['message'] = 'Oops. Something went wrong.';
        $response['container'] = 'warning';

        $userRepository = $this->getDoctrine()->getManager()->getRepository('Welcomango\Model\User');
        if ($request->request->has('query') && $request->request->get('query') != '' && $request->request->has('field') && $request->request->get('field') != '') {
            $query  = $request->request->get('query');
            $field = $request->request->get('field');
            $field = \str_replace('front_user_', '', $field);

            $result = $userRepository->findBy(array($field => $query));
            if($result){
                $response['message'] = 'username.taken';
                $response['class'] = 'alert alert-danger';
            }else{
                $response['message'] = 'username.free';
                $response['class'] = 'alert alert-success';
            }
        }
        return new JsonResponse($response);
    }
}
