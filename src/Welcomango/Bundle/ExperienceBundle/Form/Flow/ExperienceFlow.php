<?php

namespace Welcomango\Bundle\ExperienceBundle\Form\Flow;

use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use Symfony\Component\Form\FormTypeInterface;


class ExperienceFlow extends FormFlow {

    /**
     * @var FormTypeInterface
     */
    protected $formType;

    public function setFormType(FormTypeInterface $formType) {
        $this->formType = $formType;
    }

    public function getName() {
        return 'front_experience_flow';
    }

    protected function loadStepsConfig() {
        return array(
            array(
                'label' => 'The Experience',
                'form_type' => $this->formType,
            ),
            array(
                'label' => 'The Travelers',
                'form_type' => $this->formType,
            ),
            array(
                'label' => 'Your availabilities',
                'form_type' => $this->formType,

            ),
        );
    }

}