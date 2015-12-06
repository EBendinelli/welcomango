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
            $field = \str_replace('front_user_', '', $field);

            $result = $userRepository->findBy(array($field => $query));
            if ($result) {
                $response['message'] = 'username.taken';
                $response['class']   = 'alert alert-danger';
            } else {
                $response['message'] = 'username.free';
                $response['class']   = 'alert alert-success';
            }
        }

        return new JsonResponse($response);
    }
}
