<?php

namespace Welcomango\Bundle\ExperienceBundle\Form\Flow;

use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;

class CreateExperienceFlow extends FormFlow {

    protected function loadStepsConfig() {
        return array(
            array(
                'label' => 'Experience',
                'form_type' => 'Welcomango\Bundle\ExperienceBundle\Form\Type\ExperienceType',
            ),
            array(
                'label' => 'Travelers',
                'form_type' => 'Welcomango\Bundle\ExperienceBundle\Form\Type\ExperienceType',
            ),
            array(
                'label' => 'Availability',
                'form_type' => 'Welcomango\Bundle\ExperienceBundle\Form\Type\ExperienceType',
            ),
        );
    }

}
