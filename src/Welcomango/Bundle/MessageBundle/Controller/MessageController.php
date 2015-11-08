<?php

namespace Welcomango\Bundle\MessageBundle\Controller;

use FOS\MessageBundle\FormModel\NewThreadMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Model\Thread;
use Welcomango\Model\User;
use Welcomango\Model\Experience;
use Welcomango\Model\Participation;
use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;

/**
 * Class MessageController
 */
class MessageController extends BaseController
{
    /**
     * @param Request       $request
     * @param User          $currentUser
     * @param Participation $participation
     *
     * @Route("messages/{user_id}/{participation_id}", name="message_list")
     * @Template()
     *
     * @ParamConverter("currentUser", class="Welcomango\Model\User", options={"id" = "user_id"})
     * @ParamConverter("participation", class="Welcomango\Model\Participation", options={"id" = "participation_id"})
     *
     * @return array
     */
    public function requestMessageAction(Request $request, User $currentUser, Participation $participation)
    {
        $thread = $this->getRepository('Welcomango\Model\Thread')->findOneByParticipation($participation);

        if ($thread instanceof Thread) {
            return $this->forward('WelcomangoMessageBundle:Message:thread', array(
                'user_id' => $currentUser->getId(),
                'thread_id' => $thread->getId(),
            ));
        }

        $form        = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');

        if ($message = $formHandler->process($form)) {
            // TODO YOUR MESSAGE HAS BEEN SENT
        }

        return $this->render('FOSMessageBundle:Message:newThread.html.twig', array(
            'form'          => $form->createView(),
            'data'          => $form->getData(),
            'user'          => $currentUser,
            'participation' => $participation,
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
     * @Route("/messages/{thread_id}/{user_id}/current", name="message_thread")
     * @Template()
     *
     * @return array
     */
    public function threadAction(Request $request, User $currentUser, Thread $thread)
    {
        $thread = $this->getProvider()->getThread($thread->getId());
        $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler = $this->container->get('fos_message.reply_form.handler');

        return $this->render('FOSMessageBundle:Message:thread.html.twig', array(
            'form' => $form->createView(),
            'thread' => $thread
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
