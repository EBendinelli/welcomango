<?php

namespace Welcomango\Bundle\MediaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Model\Media;
use Welcomango\Bundle\MediaBundle\Form\Type\AdminMediaType;
use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;

/**
 * Class MediaController
 *
 * @Route("/welcomadmin")
 * @ParamConverter("media", options={"id" = "media_id"})
 */
class AdminMediaController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/media/list", name="admin_media_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Media')->findAll();

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
     * @Route("/media/create", name="admin_media_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $media = new Media();

        $form = $this->createForm(AdminMediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $media = $form->getData();
            $file  = $form['file']->getData();
            $this->get('welcomango.media.manager')->generateSimpleMedia($file->getClientOriginalName(), $media);
            $media->setOriginalFilename($file->getClientOriginalName());
            $media->setSize($file->getSize());

            $this->getDoctrine()->getManager()->persist($media);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $this->trans('media.created.success', array(), 'media'));

            return $this->redirect($this->generateUrl('admin_media_list'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @param Request $request
     * @param Media   $media
     *
     * @Route("/media/{media_id}/edit", name="admin_media_edit")
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, Media $media)
    {
        $form = $this->createForm(AdminMediaType::class, $media);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $media = $form->getData();
            $file  = $form['file']->getData();
            $this->get('welcomango.media.manager')->generateSimpleMedia($file->getClientOriginalName(), $media);
            $this->getDoctrine()->getManager()->persist($media);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('media.edit.success', array(), 'media'));

            return $this->redirect($this->generateUrl('admin_media_list'));
        }

        return array(
            'form'  => $form->createView(),
            'media' => $media,
        );
    }

    /**
     * @param Media $media
     *
     * @Route("/media/{media_id}/delete", name="admin_media_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Media $media)
    {
        $this->getDoctrine()->getManager()->remove($media);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_media_list'));
    }
}
