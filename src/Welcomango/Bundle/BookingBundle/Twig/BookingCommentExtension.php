<?php

namespace Welcomango\Bundle\BookingBundle\Twig;

use Symfony\Component\Translation\TranslatorInterface;

use Welcomango\Model\User;
use Welcomango\Model\Booking;

/**
 * Class BookingCommentExtension
 */
class BookingCommentExtension extends \Twig_Extension
{

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
     * @param Booking $booking
     * @param User $user
     *
     * @return string
     */
    public function bookingComment(Booking $booking, User $user, $userSide)
    {
        $response = '<div class="text-center hint-text">'.$this->translator->trans('booking.noComment', array(), 'interface').'</div>';

        if($comments = $booking->getComments()){
            foreach($comments as $comment){
                if($userSide == 'poster'){
                    if($comment->getPoster() == $user) {
                        if ($comment->isValidated()){
                            $response = '<div style="font-style: italic; white-space:normal">' . $comment->getBody() . '</div>';
                        }else {
                            $response = '<div class="hint-text text-center">'.$this->translator->trans('booking.commentWaitingForApproval', array(), 'interface').'</div>';
                        }
                    }
                }elseif($userSide == 'receiver'){
                    if($comment->getReceiver() == $user && $comment->isValidated()){
                        $response = '<div style="font-style: italic; white-space:normal">'.$comment->getBody().'</div>';
                    }
                }
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
