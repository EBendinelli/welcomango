<?php

namespace Welcomango\Bundle\CoreBundle\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use Welcomango\Model\User;
use Welcomango\Model\Participation;

/**
 * Class DisplayRoleIconExtension
 */
class NotificationDisplayExtension extends \Twig_Extension
{

    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    /**
     * __construct
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager   = $entityManager;
    }



    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('notification_display', array($this, 'notificationDisplay'), array('is_safe' => array('html')))
        );
    }

    /**
     * @param mixed $role
     *
     * @return string
     */
    public function notificationDisplay($user, $type)
    {
        $icon = '';
        $proposedExperience = $user->getExperience();

        switch($type){
            case 'received':
                if(empty($proposedExperience)){ break; }

                $pendingRequest = $this->entityManager->getRepository('Welcomango\Model\Participation')->findBy(array('experience' => $proposedExperience, 'isParticipant' => 1, 'status' => array('Requested')));
                if(count($pendingRequest))
                    $icon = '<div class="notification-icon">'.count($pendingRequest).'</div>';
                break;
            case 'sent':
                $sentRequest = $this->entityManager->getRepository('Welcomango\Model\Participation')->findBy(array('user' => $user, 'isParticipant' => 1, 'status' => array('Accepted')));
                if(count($sentRequest))
                    $icon = '<div class="notification-icon">'.count($sentRequest).'</div>';
                break;
        }

        return $icon;

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'notification_display_extension';
    }
}
