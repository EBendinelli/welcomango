<?php

namespace Welcomango\Bundle\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;

/**
 * Class AdminCoreController
 */
class AdminCoreController extends BaseController
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
