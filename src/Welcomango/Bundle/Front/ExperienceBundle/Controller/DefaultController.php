<?php

namespace Welcomango\Bundle\Front\ExperienceBundle\Controller;

use Proxies\__CG__\Welcomango\Model\Experience;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WelcomangoFrontExperienceBundle:Default:index.html.twig', array('name' => $name));
    }
}
