<?php

namespace Welcomango\Bundle\BookingBundle\Twig;

use Symfony\Component\Translation\TranslatorInterface;

use Welcomango\Model\User;
use Welcomango\Model\Feedback;

/**
 * Class BookingCommentExtension
 */
class BookingCommentExtension extends \Twig_Extension
{

    /**
     * @var TranslatorInterface $translator
     */
    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('booking_comment', [$this, 'bookingComment'], ['is_safe' => ['html']])
        );
    }

    /**
     * @param Feedback $feedback
     * @param User $user
     *
     * @return string
     */
    public function bookingComment(Feedback $feedback, User $user, $userSide)
    {
        $response = '<div class="text-center hint-text">'.$this->translator->trans('booking.noComment', array(), 'interface').'</div>';

        if($userSide == 'poster'){
            if($feedback->getSender() == $user) {
                if ($feedback->isValidated() && !$feedback->isDeleted()){
                    $response = '<div style="font-style: italic; white-space:normal">' . $feedback->getComment() . '</div>';
                }else {
                    $response = '<div class="hint-text text-center">'.$this->translator->trans('booking.commentWaitingForApproval', array(), 'interface').'</div>';
                }
            }
        }elseif($userSide == 'receiver'){
            if($feedback->getReceiver() == $user && $feedback->isValidated() && !$feedback->isDeleted()){
                $response = '<div style="font-style: italic; white-space:normal">'.$feedback->getComment().'</div>';
            }
        }


        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'booking_comment_extension';
    }
}
