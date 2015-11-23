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

        $formName       = $request->request->get('formName');
        $inputName      = $request->request->get('inputName');
        $templateReturn = $request->request->get('templateReturn');

        if ($request->isXmlHttpRequest()) {
            if (count($request->files->all()[$formName][$inputName]) > 10) {
                $errors = array(
                    "label"  => $this->get('translator')->trans('too.many.files'),
                    "status" => -1,
                );

                return new JsonResponse(json_encode($errors));
            }

            if (count($request->files->all()[$formName][$inputName]) == 1) {
                if (is_array($request->files->all()[$formName][$inputName])) {
                    $process = $request->files->all()[$formName][$inputName];
                } else {
                    $process[] = $request->files->all()[$formName][$inputName];
                }
            } else {
                $process = $request->files->all()[$formName][$inputName];
            }

            foreach ($process as $file) {
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

                return $this->render($templateReturn, array(
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

    /**
     * @param Request $request
     *
     * @Route("/experience/media/crop", name="media_crop")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function mediaCropAction(Request $request)
    {
        ldd($request->request->all());
        $targetWidth = $request->request->get('w');
        $targetHeight = $request->request->get('h');
        $jpeg_quality = 90;

        $src = 'demo_files/flowers.jpg';
        $img_r = imagecreatefromjpeg($src);
        $dst_r = ImageCreateTrueColor( $targetWidth, $targetHeight );

        imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
            $targ_w,$targ_h,$_POST['w'],$_POST['h']);

        header('Content-type: image/jpeg');
        imagejpeg($dst_r, null, $jpeg_quality);

        return new JsonResponse();
    }
}
