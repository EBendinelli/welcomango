<?php

namespace Welcomango\Bundle\ExperienceBundle\Form\Flow;

use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;

use Welcomango\Bundle\ExperienceBundle\Form\Type\ExperienceStep1Type;
use Welcomango\Bundle\ExperienceBundle\Form\Type\ExperienceStep2Type;
use Welcomango\Bundle\ExperienceBundle\Form\Type\ExperienceStep3Type;

class ExperienceFlow extends FormFlow {

    public function getName() {
        return 'front_experience_flow';
    }

    protected function loadStepsConfig() {
        return array(
            array(
                'label' => 'The Experience',
                'form_type' => new ExperienceStep1Type(),
            ),
            array(
                'label' => 'The Travelers',
                'form_type' => new ExperienceStep2Type(),
            ),
            array(
                'label' => 'Your availabilities',
                'form_type' => new ExperienceStep3Type(),
            ),
            array(
                'label' => 'Overview',
            ),
        );
    }

}