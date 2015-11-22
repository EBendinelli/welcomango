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
 */
class MediaController extends BaseController
{
    /**
     * Process, upload and render images  for experiences form !
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

    /**
     *
     * Process delete for a non used media (experience front form)
     *
     * @param Request $request
     *
     * @Route("/experience/media/delete", name="experience_media_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function mediaDeleteAction(Request $request)
    {
        $mediaId = $request->request->get('mediaId');

        $media = $this->getRepository('Welcomango\Model\Media')->findOneBy([
            'id' => $mediaId,
            'temp' => true,
        ]);

        $this->getDoctrine()->getManager()->remove($media);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse();
    }
}
