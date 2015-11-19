<?php

namespace Welcomango\Bundle\AvailabilityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WelcomangoAvailabilityBundle:Default:index.html.twig', array('name' => $name));
    }
}
