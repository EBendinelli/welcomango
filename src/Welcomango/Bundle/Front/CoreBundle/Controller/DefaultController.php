<?php

namespace Welcomango\Bundle\Front\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\Front\CoreBundle\Controller\Controller;
use Welcomango\Model\Experience;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="front_homepage")
     * @Method({"GET", "POST"})
     */
    public function indexAction()
    {
        // Loading Featured Experiences
        $featuredExperiences = $this
            ->getRepository('Welcomango\Model\Experience')
            ->getFeatured(3);

        return $this->render('WelcomangoFrontCoreBundle:Default:index.html.twig', array(
            'featuredExperiences' => $featuredExperiences
        ));
    }
}
