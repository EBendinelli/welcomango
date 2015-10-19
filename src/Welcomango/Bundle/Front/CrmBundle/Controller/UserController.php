<?php

namespace Welcomango\Bundle\Front\CrmBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\Front\CoreBundle\Controller\Controller;
use Welcomango\Model\Experience;
use Welcomango\Model\User;

/**
 * Class UserController
 *
 * @ParamConverter("user", options={"id" = "user_id"})
 */
class UserController extends Controller
{

        /**
         * @param Request $request
         * @param User $user
         *
         * @Route("/user/{user_id}", name="front_user_view")
         * @ParamConverter("user", options={"id" = "user_id"})
         * @Template()
         *
         * @return array
         */
        public function viewAction(Request $request, User $user)
        {
            $proposedExperiences = $this
                ->getRepository('Welcomango\Model\Experience')
                ->findAllExperiencesCreatedByUser($user);

            $attendedExperiences = $this
                ->getRepository('Welcomango\Model\Experience')
                ->findAllExperiencesAttendedByUser($user);

            return $this->render('WelcomangoFrontCrmBundle:User:view.html.twig', array(
                'user' => $user,
                'proposedExperiences' => $proposedExperiences,
                'attendedExperiences' => $attendedExperiences
            ));

        }
}
