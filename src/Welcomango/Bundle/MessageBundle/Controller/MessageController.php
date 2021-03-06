<?php

namespace Welcomango\Bundle\MessageBundle\Controller;

use FOS\MessageBundle\FormModel\NewThreadMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Model\Thread;
use Welcomango\Model\User;
use Welcomango\Model\Experience;
use Welcomango\Model\Booking;
use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;

/**
 * Class MessageController
 */
class MessageController extends BaseController
{
    /**
     * @param Request $request
     * @param User    $currentUser
     * @param Booking $booking
     *
     * @Route("messages/{user_id}/{booking_id}", name="message_request")
     * @Template()
     *
     * @ParamConverter("currentUser", class="Welcomango\Model\User", options={"id" = "user_id"})
     * @ParamConverter("booking", class="Welcomango\Model\Booking", options={"id" = "booking_id"})
     *
     * @return array
     */
    public function requestMessageAction(Request $request, User $currentUser, Booking $booking)
    {
        $thread = $this->getRepository('Welcomango\Model\Thread')->findOneByBooking($booking);

        if ($thread instanceof Thread) {
            return $this->forward('WelcomangoMessageBundle:Message:thread', array(
                'user_id'   => $currentUser->getId(),
                'thread_id' => $thread->getId(),
            ));
        } else {
            return $this->forward('WelcomangoMessageBundle:Message:newThread', array(
                'user_id'    => $currentUser->getId(),
                'booking_id' => $booking->getId(),
            ));
        }
    }

    /**
     * Displays the authenticated participant inbox
     *
     * @Route("messages/inbox", name="message_inbox")
     *
     * @return Response
     */
    public function inboxAction()
    {
        $threads = $this->getProvider()->getInboxThreads();

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:inbox.html.twig', array(
            'threads' => $threads,
        ));
    }

    /**
     * @param Request $request
     * @param User    $currentUser
     * @param Booking $booking
     *
     * @ParamConverter("currentUser", class="Welcomango\Model\User", options={"id" = "user_id"})
     * @ParamConverter("booking", class="Welcomango\Model\Booking", options={"id" = "booking_id"})
     *
     * @Route("/message/new/{user_id}/current", name="message_thread")
     * @Template()
     *
     * @return array
     */
    public function newThreadAction(Request $request, User $currentUser, Booking $booking)
    {
        $form = $this->createForm($this->get('welcomango.new.thread.type'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            if (isset($form->getData()['message'])) {
                //In this cas the message is coming from the request made on the experience page
                $message = $form->getData()['message'];
            } else {
                //This is the basic key designed by the FOSMessageBundle view
                $message = $form->getData()['body'];
            }

            if ($booking->getUser() != $currentUser) {
                $recipient = $booking->getUser();
            } else {
                $recipient = $booking->getExperience()->getCreator();
            }
            $thread = $this->get('welcomango.message.creator')->createThread($booking, $currentUser, $recipient, $message);

            return $this->redirectToRoute('message_thread_view', array(
                'user_id'   => $currentUser->getId(),
                'thread_id' => $thread->getId(),
            ));
        }

        return $this->render('FOSMessageBundle:Message:newThread.html.twig', array(
            'form'    => $form->createView(),
            'data'    => $form->getData(),
            'user'    => $currentUser,
            'booking' => $booking,
        ));
    }

    /**
     * @param Request $request
     * @param User    $currentUser
     * @param Thread  $thread
     *
     * @ParamConverter("currentUser", class="Welcomango\Model\User", options={"id" = "user_id"})
     * @ParamConverter("thread", class="Welcomango\Model\Thread", options={"id" = "thread_id"})
     *
     * @Route("/messages/{thread_id}/{user_id}/current", name="message_thread_view")
     * @Template()
     *
     * @return array
     */
    public function threadAction(Request $request, User $currentUser, Thread $thread)
    {
        if ($currentUser !=  $this->getUser()) {
            return $this->render('WelcomangoCoreBundle:CRUD:notAllowed.html.twig');
        }

        $thread      = $this->getProvider()->getThread($thread->getId());
        $form        = $this->container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler = $this->container->get('fos_message.reply_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('message_thread_view', array(
                'thread_id' => $message->getThread()->getId(),
                'user_id' => $currentUser->getId(),
            )));
        }


        return $this->render('FOSMessageBundle:Message:thread.html.twig', array(
            'form'   => $form->createView(),
            'thread' => $thread,
            'user'   => $currentUser,
        ));
    }

    /**
     * Gets the provider service
     *
     * @return ProviderInterface
     */
    protected function getProvider()
    {
        return $this->container->get('fos_message.provider');
    }
}
