<?php

namespace Welcomango\Bundle\Front\CrmBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseProfileController;
use FOS\UserBundle\Model\UserInterface;

use Welcomango\Model\Experiences;

class ProfileController extends BaseProfileController
{

    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $userExperiences = $entityManager
            ->getRepository('Welcomango\Model\Experience')
            ->findAllExperiencesCreatedByUser($user);

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'user' => $user,
            'userExperiences' => $userExperiences
        ));
    }

    protected function getRedirectionUrl(UserInterface $user)
    {
        // Change the redirection target after saving the profile
        return $this->container->get('router')->generate('fos_user_profile_edit');
    }

}