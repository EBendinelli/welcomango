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
     * @Route("/admin", name="admin_homepage")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
