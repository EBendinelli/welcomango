<?php

namespace Welcomango\Bundle\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Page;

/**
 * Class AdminContentController
 *
 * @Route("/welcomadmin")
 * @ParamConverter("page", options={"id" = "page_id"})
 */
class AdminContentController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/page/list", name="admin_page_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Page')->findAll();
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
     * @Route("/page/create", name="admin_page_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $page = new Page();
        $page->setCreatedAt(new \DateTime());
        $page->setUpdatedAt(new \DateTime());
        $form = $this->createForm($this->get('welcomango.form.page.create'), $page);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            $this->addFlash('success', $this->trans('page.created.success', array(), 'page'));

            return $this->redirect($this->generateUrl('admin_page_list'));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request    $request
     * @param Page $page
     *
     * @Route("/page/{page_id}/edit", name="admin_page_edit")
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, Page $page)
    {
        $form = $this->createForm($this->get('welcomango.admin.form.page.create'), $page);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $page->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->persist($page);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('page.edit.success', array(), 'crm'));

            return $this->redirect($this->generateUrl('admin_page_list'));
        }

        return array(
            'form' => $form->createView(),
            'page'  => $page
        );
    }

    /**
     * @param Page $page
     *
     * @Route("/page/{page_id}/delete", name="admin_page_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Page $page)
    {
        $page->setDeleted(true);
        $page->setPublicationStatus('deleted');
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_page_list'));
    }

    /**
     * @param Request $request
     *
     * @Route("/page/_page_search_ajax", name="admin_page_search_ajax")
     * @Method("POST")
     *
     * @return array
     */
    public function ajaxSearchAction(Request $request)
    {
        $query       = $request->request->get('query');
        $page = $this->getRepository('Welcomango\Model\Page')->findByQuery($query);

        return array(
            'page' => $page
        );
    }
}
