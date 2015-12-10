<?php

namespace Welcomango\Bundle\BookingBundle\Controller;

use Symfony\Component\DependencyInjection\Tests\Compiler\F;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Feedback;

/**
 * Class AdminFeedbackController
 *
 * @Route("/welcomadmin")
 * @ParamConverter("feedback", options={"id" = "feedback_id"})
 */
class AdminFeedbackController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/feedback/list", name="admin_feedback_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Feedback')->findAll();
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            50
        );

        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/feedback/create", name="admin_feedback_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $feedback = new Feedback();
        $form = $this->createForm($this->get('welcomango.admin.form.feedback.type'), $feedback);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();

            $this->addFlash('success', $this->trans('feedback.created.success', array(), 'feedback'));

            return $this->redirect($this->generateUrl('admin_feedback_list'));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request    $request
     * @param Feedback $feedback
     *
     * @Route("/feedback/{feedback_id}/edit", name="admin_feedback_edit")
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, Feedback $feedback)
    {
        $form = $this->createForm($this->get('welcomango.admin.form.feedback.type'), $feedback);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($feedback);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('feedback.edit.success', array(), 'crm'));

            return $this->redirect($this->generateUrl('admin_feedback_list'));
        }

        return array(
            'form'           => $form->createView(),
            'requested_feedback' => $feedback
        );
    }

    /**
     * @param Feedback $feedback
     *
     * @Route("/feedback/{feedback_id}/delete", name="admin_feedback_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Feedback $feedback)
    {

        $this->getDoctrine()->getManager()->remove($feedback);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_feedback_list'));
    }

    /**
     * @param Feedback $feedback
     *
     * @Route("/feedback/{feedback_id}/validate", name="admin_feedback_validate")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validateAction(Feedback $feedback)
    {
        $feedback->setValidated(true);

        $this->getDoctrine()->getManager()->persist($feedback);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', $this->trans('feedback.validated', array(), 'feedback'));

        return $this->redirect($this->generateUrl('admin_moderation_feedback'));
    }

    /**
     * @param Feedback $feedback
     *
     * @Route("/feedback/{feedback_id}/refuse", name="admin_feedback_refuse")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function refuseAction(Feedback $feedback)
    {
        $feedback->setDeleted(true);

        $this->getDoctrine()->getManager()->persist($feedback);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', $this->trans('feedback.refused', array(), 'feedback'));

        return $this->redirect($this->generateUrl('admin_moderation_feedback'));
    }



    /**
     * @param Request $request
     *
     * @Route("/moderation/feedback", name="admin_moderation_feedback")
     * @Template()
     *
     * @return array
     */
    public function moderationListAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Feedback')->findBy(['validated' => 0]);
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            10
        );

        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/feedback/_feedback_search_ajax", name="admin_feedback_search_ajax")
     * @Method("POST")
     *
     * @return array
     */
    public function ajaxSearchAction(Request $request)
    {
        $query       = $request->request->get('query');
        $feedback = $this->getRepository('Welcomango\Model\Feedback')->findByQuery($query);

        return array(
            'feedback' => $feedback
        );
    }
}
