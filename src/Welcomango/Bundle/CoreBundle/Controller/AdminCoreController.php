<?php

namespace Welcomango\Bundle\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Feedback;

/**
 * Class AdminCoreController
 */
class AdminCoreController extends BaseController
{
    /**
     * @Route("/welcomadmin/", name="admin_homepage")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {


        //Get feedbacks waiting for moderation
        $feedbacks = $this->getRepository('Welcomango\Model\Feedback')->findBy(['validated' => false, 'deleted' => false ]);
        $feedbacksCount = count($feedbacks);

        //Get new users
        $newUsers = $this->getRepository('Welcomango\Model\User')->findBy(['createdAt' => new \Datetime(date('d-m-Y'))]);

        //Get experience waiting for validation
        $experiences = $this->getRepository('Welcomango\Model\Experience')->findBy(['publicationStatus' => 'pending', 'deleted' => false ]);
        $experiencesCount = count($experiences);

        return array(
            'feedbacksCount' => $feedbacksCount,
            'newUsers' => $newUsers,
            'experiencesCount' => $experiencesCount,
        );
    }
}
