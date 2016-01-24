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
 */
class UserController extends BaseController
{
    /**
     * @param Request $request
     * @param User    $user
     *
     * @Route("/user/{slug}", name="front_user_view")
     * @ParamConverter("user", options={"slug" = "slug"})
     * @Template()
     *
     * @return array
     */
    public function viewAction(Request $request, User $user)
    {
        $proposedExperiences = $user->getPublishedExperiences();

        $attendedExperiences = $this
            ->getRepository('Welcomango\Model\Experience')
            ->findAllExperiencesAttendedByUser($user);

        //Get comment
        $feedbacks = $user->getDisplayableReceivedFeedbacks();

        return $this->render('WelcomangoUserBundle:User:view.html.twig', array(
            'user'                => $user,
            'proposedExperiences' => $proposedExperiences,
            'attendedExperiences' => $attendedExperiences,
            'feedbacks'           => $feedbacks,
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/check-email", name="front_user_confirm_email")
     * @Template()
     *
     * @return array
     */
    public function confirmEmailAction(Request $request)
    {
        return array();
    }
}
