<?php

namespace Welcomango\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Experience;

class CoreController extends BaseController
{
    /**
     * @Route("/", name="front_homepage")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction()
    {
        // Loading Featured Experiences
        $featuredExperiences = $this
            ->getRepository('Welcomango\Model\Experience')
            ->getFeatured(3);

        $bestExperiences = $this
            ->getRepository('Welcomango\Model\Experience')
            ->getBestRated(4);

        return array(
            'featuredExperiences' => $featuredExperiences,
            'bestExperiences' => $bestExperiences
        );
    }
}
