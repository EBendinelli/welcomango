<?php

namespace Welcomango\Bundle\MediaBundle\Controller;

use Gaufrette\Filesystem;
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
     * We need to create a media in BDD in order to have IDs
     * file uploading is handle by gaufrette
     *
     * @param Request $request
     *
     * @Route("/experience/media/upload", name="experience_media_upload")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function mediaUploadAction(Request $request)
    {
        $originalFilename = $request->request->get('file');
        $tmpFilename      = $this->get('welcomango.media_namer')->getTempName($originalFilename);

        $originalFilename = $request->request->get('file');
        $tmpFilename      = $this->get('welcomango.media_namer')->getTempName($originalFilename);

        $media = new Media();
        $media->setOriginalFilename($originalFilename);

        $this->getDoctrine()->getManager()->persist($media);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(json_encode($media->getId()));
    }

    /**
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
        $originalFilename = $request->request->get('file');
        $tmpFilename      = $this->get('welcomango.media_namer')->getTempName($originalFilename);

        $tmpAdapter = $this->get('knp_gaufrette.filesystem_map')->get('gallery');
        $tmpAdapter->get($tmpFilename)->delete();

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
        $targetWidth  = $request->request->get('w');
        $targetHeight = $request->request->get('h');
        $jpeg_quality = 90;

        $src   = 'demo_files/flowers.jpg';
        $img_r = imagecreatefromjpeg($src);
        $dst_r = ImageCreateTrueColor($targetWidth, $targetHeight);

        imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'],
            $targ_w, $targ_h, $_POST['w'], $_POST['h']);

        header('Content-type: image/jpeg');
        imagejpeg($dst_r, null, $jpeg_quality);

        return new JsonResponse();
    }
}
