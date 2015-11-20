<?php

namespace Welcomango\Bundle\ExperienceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Welcomango\Model\Participation;

/**
 * ExperienceStep3Type Form class
 */
class ExperienceStep3Type extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $availabilities = array('form.experience.alwaysAvailable', 'form.experience.specificAvailability');
        $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $hours = array('Early Morning', 'Morning', 'Lunchtime', 'Afternoon', 'Evening', 'Night');
        $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

        $builder->add('availability', 'choice',[
            'choices' => $availabilities,
            'label' => 'form.experience.availability',
            'mapped'   => false,
            'expanded' => true,
            'multiple' => false,
            'label' => false,
            'data' => 0,
        ]);

        $builder->add('day', 'choice',[
            'choices' => $days,
            'label' => false,
            'mapped'   => false,
            'expanded' => true,
            'multiple' => true,
            'data' => array("5", "6"),
        ]);

        $builder->add('hour', 'choice',[
            'choices' => $hours,
            'label' => false,
            'mapped'   => false,
            'expanded' => true,
            'multiple' => true,
        ]);

        /*$builder->add('month', 'choice',[
            'choices' => $months,
            'label' => false,
            'mapped'   => false,
            'expanded' => true,
            'multiple' => true,
        ]);*/

        $builder->add('start_date', 'date', [
            'label'    => 'form.experience.startDate',
            'data'     => new \DateTime(),
            'required' => false,
            'mapped'   => false,
            'years'    => range(date('Y'), date('Y') + 1),
            'months'   => range(date('m'), 12),
            'days'     => range(date('d'), 31),
            'widget'   => 'single_text',
            'format'   => 'dd-MM-yyyy',
            'attr'     => [
                'class'            => 'form-control input-inline datepicker',
                'data-provide'     => 'datepicker',
                'data-date-format' => 'dd-mm-yyyy',
            ],
        ]);

        $builder->add('end_date', 'date', [
            'label'    => 'form.experience.endDate',
            'data'     => new \DateTime(),
            'required' => false,
            'mapped'   => false,
            'years'    => range(date('Y'), date('Y') + 1),
            'months'   => range(date('m'), 12),
            'days'     => range(date('d'), 31),
            'widget'   => 'single_text',
            'format'   => 'dd-MM-yyyy',
            'attr'     => [
                'class'            => 'form-control input-inline datepicker',
                'data-provide'     => 'datepicker',
                'data-date-format' => 'dd-mm-yyyy',
            ],
        ]);

    }


    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'front_experience_step3';
    }

}