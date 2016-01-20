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
        $targetWidth  = (int) $request->request->get('w');
        $targetHeight = (int) $request->request->get('h');
        $xPos         = $request->request->get('x');
        $yPos         = $request->request->get('y');
        $src          = $request->request->get('tempName');
        $jpegQuality  = 100;

        $source        = imagecreatefromjpeg('/home/jaybe/www/welcomango/web'.$src);
        $resourceImage = imagecreatetruecolor($targetWidth, $targetHeight);

        imagecopyresampled($resourceImage, $source, 0, 0, $xPos, $yPos, $targetWidth, $targetHeight, $targetWidth, $targetHeight);

        header('Content-type: image/jpeg');
        imagejpeg($resourceImage, '/home/jaybe/www/welcomango/web/medias/resize/filename.jpg', $jpegQuality);

        return new Response();
    }
}
