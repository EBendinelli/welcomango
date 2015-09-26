<?php

namespace Welcomango\Bundle\Admin\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Welcomango\Bundle\Admin\CoreBundle\Controller\Controller;

/**
 * Class DefaultController
 */
class DefaultController extends Controller
{
    /**
     * @Route("/welcomadmin", name="admin_homepage")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return array();
    }
}
