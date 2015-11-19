<?php

namespace Welcomango\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Experience;
use Welcomango\Model\User;

/**
 * Class UserController
 *
 * @ParamConverter("user", options={"id" = "user_id"})
 */
class UserController extends BaseController
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
            $proposedExperiences = $user->getExperiences();

            $attendedExperiences = $this
                ->getRepository('Welcomango\Model\Experience')
                ->findAllExperiencesAttendedByUser($user);

            return $this->render('WelcomangoUserBundle:User:view.html.twig', array(
                'user' => $user,
                'proposedExperiences' => $proposedExperiences,
                'attendedExperiences' => $attendedExperiences
            ));

        }
}
