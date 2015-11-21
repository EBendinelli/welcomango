<?php

namespace Welcomango\Bundle\TagBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Tag;

/**
 * Class AdminTagController
 *
 * @Route("/welcomadmin")
 * @ParamConverter("tag", options={"id" = "tag_id"})
 */
class AdminTagController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/tag/list", name="admin_tag_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Tag')->findAll();
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
     * @Route("/tag/create", name="admin_tag_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $tag = new Tag();
        $tag->setCreatedAt(new \DateTime());
        $tag->setUpdatedAt(new \DateTime());
        $form = $this->createForm($this->get('welcomango.form.tag.create'), $tag);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            $this->addFlash('success', $this->trans('tag.created.success', array(), 'tag'));

            return $this->redirect($this->generateUrl('admin_tag_list'));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request    $request
     * @param Tag $tag
     *
     * @Route("/tag/{tag_id}/edit", name="admin_tag_edit")
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, Tag $tag)
    {
        $form = $this->createForm($this->get('welcomango.form.tag.create'), $tag);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $tag->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->persist($tag);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('tag.edit.success', array(), 'crm'));

            return $this->redirect($this->generateUrl('admin_tag_list'));
        }

        return array(
            'form'           => $form->createView(),
            'tag' => $tag
        );
    }

    /**
     * @param Tag $tag
     *
     * @Route("/tag/{tag_id}/delete", name="admin_tag_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Tag $tag)
    {

        $this->getDoctrine()->getManager()->remove($tag);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_tag_list'));
    }

    /**
     * @param Request $request
     *
     * @Route("/tag/_tag_search_ajax", name="admin_tag_search_ajax")
     * @Method("POST")
     *
     * @return array
     */
    public function ajaxSearchAction(Request $request)
    {
        $query       = $request->request->get('query');
        $tag = $this->getRepository('Welcomango\Model\Tag')->findByQuery($query);

        return array(
            'tag' => $tag
        );
    }
}
