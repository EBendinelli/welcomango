<?php

namespace Welcomango\Bundle\ExperienceBundle\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;

use Welcomango\Model\User;

class ExperienceVoter extends AbstractVoter
{
    const DELETE = 'delete';
    const EDIT = 'edit';

    protected function getSupportedAttributes()
    {
        return array(self::DELETE, self::EDIT);
    }

    protected function getSupportedClasses()
    {
        return array('Welcomango\Model\Experience');
    }

    protected function isGranted($attribute, $experience, $user = null)
    {
        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return false;
        }

        // double-check that the User object is the expected entity (this
        // only happens when you did not configure the security system properly)
        if (!$user instanceof User) {
            throw new \LogicException('The user is somehow not our User class!');
        }

        switch($attribute) {
            case self::DELETE:
                // Check that the user is the creator of the experience
                if ($user->getId() === $experience->getCreator()->getId()) {
                    return true;
                }

                break;
            case self::EDIT:
                // Check that the user is the creator of the experience
                if ($user->getId() === $experience->getCreator()->getId()) {
                    return true;
                }

                break;
        }

        return false;
    }
}
