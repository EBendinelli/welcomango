<?php

namespace Welcomango\Bundle\Admin\ExperienceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\Admin\CoreBundle\Controller\Controller;
use Welcomango\Model\Experience;

/**
 * Class ExperienceController
 *
 * @Route("/welcomadmin")
 * @ParamConverter("experience", options={"id" = "experience_id"})
 */
class ExperienceController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/experience/list", name="admin_experience_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Experience')->findAll();
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
     * @Route("/experience/create", name="admin_experience_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $experience = new Experience();

        $form = $this->createForm($this->get('welcomango.form.experience.create'), $experience);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($experience);
            $em->flush();

            $this->addFlash('success', $this->trans('experience.created.success', array(), 'experience'));

            return $this->redirect($this->generateUrl('experience_list'));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request    $request
     * @param Experience $experience
     *
     * @Route("/experience/{experience_id}/edit", name="admin_experience_edit")
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, Experience $experience)
    {
        $form = $this->createForm($this->get('welcomango.form.experience.edit'), $experience);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($experience);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('experience.edit.success', array(), 'crm'));

            return $this->redirect($this->generateUrl('admin_experience_list'));
        }

        return array(
            'form'           => $form->createView(),
            'requested_experience' => $experience
        );
    }

    /**
     * @param Experience $experience
     *
     * @Route("/experience/{experience_id}/delete", name="admin_experience_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Experience $experience)
    {

        $this->getDoctrine()->getManager()->remove($experience);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_experience_list'));
    }

    /**
     * @param Request $request
     *
     * @Route("/experience/_experience_search_ajax", name="admin_experience_search_ajax")
     * @Method("POST")
     *
     * @return array
     */
    public function ajaxSearchAction(Request $request)
    {
        $query       = $request->request->get('query');
        $experiences = $this->getRepository('Welcomango\Model\Experience')->findByQuery($query);

        return array(
            'experience' => $experiences
        );
    }
}
