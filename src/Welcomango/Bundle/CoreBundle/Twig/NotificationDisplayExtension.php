<?php

namespace Welcomango\Bundle\CoreBundle\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use FOS\MessageBundle\Provider\Provider;

use Welcomango\Model\User;
use Welcomango\Model\Booking;

/**
 * Class DisplayRoleIconExtension
 */
class NotificationDisplayExtension extends \Twig_Extension
{

    /**
     * @var Provider $messageProvider
     */
    protected $messageProvider;

    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     * @param Provider      $messageProvider
     */
    public function __construct(EntityManager $entityManager, Provider $messageProvider)
    {
        $this->entityManager   = $entityManager;
        $this->messageProvider = $messageProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('notification_display', array($this, 'notificationDisplay'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param User   $user
     * @param string $type
     *
     * @return string
     */
    public function notificationDisplay(User $user, $type)
    {
        $icon               = '';
        $proposedExperiences = $user->getExperiences();

        switch ($type) {
            case 'received':
                if ($proposedExperiences->isEmpty()) {
                    break;
                }

                foreach($proposedExperiences as $experience){
                    $experienceIds[] = $experience->getId();
                }
                $pendingRequest = $this->entityManager->getRepository('Welcomango\Model\Booking')->findBy(array('experience' => $experienceIds, 'status' => array('Requested')));
                if (count($pendingRequest)) {
                    $icon = '<div class="notification-icon">'.count($pendingRequest).'</div>';
                }
                break;
            case 'sent':
                $sentRequest = $this->entityManager->getRepository('Welcomango\Model\Booking')->findBy(array('user' => $user,  'status' => array('Accepted')));
                if (count($sentRequest)) {
                    $icon = '<div class="notification-icon">'.count($sentRequest).'</div>';
                }
                break;
            case 'inbox':
                $countNewMessages = $this->messageProvider->getNbUnreadMessages();
                if ($countNewMessages > 0) {
                    $icon = '<div class="notification-icon">'.$countNewMessages.'</div>';
                }
                break;
            case 'message':
                $icon = '<div class="notification-icon message-unread-icon"><i class="fa fa-envelope fa-1x"></i></div>';
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
