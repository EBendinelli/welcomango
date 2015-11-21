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

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Media;

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

        $form = $this->createForm($this->get('welcomango.form.media.type'), $media);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $media = $form->getData();
            $file  = $media->getFile();

            $media->setFilename($file->getBasename('.'.$file->guessExtension()));
            $media->setExtension($file->guessExtension());
            $media->setSize($file->getSize());
            $media->setMimeType($file->getMimeType());

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
        $form = $this->createForm($this->get('welcomango.form.media.type'), $media);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $media->upload();
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

    /**
     * Process and render form filters
     *
     * @param Request $request
     *
     * @Route("/experience/media/delete", name="experience_media_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function mediaDeleteAction(Request $request)
    {
        // todo some code to delete the media
    }

    /**
     * Process, upload and render images !
     *
     * @param Request $request
     *
     * @Route("/experience/media/upload", name="experience_media_upload")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function mediaUploadAction(Request $request)
    {
        $errors    = array();
        $mediaList = array();

        if ($request->isXmlHttpRequest()) {
            if (count($request->files->all()['front_experience']['medias']) > 10) {
                $errors = array(
                    "label"  => $this->get('translator')->trans('too.many.files'),
                    "status" => -1,
                );

                return new JsonResponse(json_encode($errors));
            }

            foreach ($request->files->all()['front_experience']['medias'] as $file) {
                if ($file->getSize() > 5 * Media::MB) {
                    $errors = array(
                        "label"  => $this->get('translator')->trans('incorect.file.size'),
                        "status" => -1,
                    );

                    return new JsonResponse(json_encode($errors));
                } elseif (in_array($file->getMimeType(), Media::availableMimeType())) {
                    $media = new Media();
                    $media->setFile($file);
                    $media->setTemp(true);
                    $media->setOriginalFilename($file->getClientOriginalName());
                    $media->setSize($file->getSize());
                    $media->setPath('/upload/medias/tmp/'.$file->getClientOriginalName());
                    $media->setExtension($file->guessExtension());
                    $media->setMimeType($file->getMimeType());

                    $file->move($media->getUploadTmpRootDir(), $file->getClientOriginalName());
                    $this->getDoctrine()->getManager()->persist($media);

                    $mediaList[] = $media;

                } else {
                    $errors = array(
                        "label"  => $this->get('translator')->trans('incorect.file.mime_type'),
                        "status" => -1,
                    );

                    return new JsonResponse(json_encode($errors));
                }
            }

            if (empty($errors)) {
                $this->getDoctrine()->getManager()->flush();

                return $this->render('WelcomangoExperienceBundle:Experience:_renderImages.html.twig', array(
                    'images' => $mediaList,
                ));
            }
        }
    }
}
