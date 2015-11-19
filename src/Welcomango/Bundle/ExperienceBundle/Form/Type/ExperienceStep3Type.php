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
        $times= array('Early Morning', 'Morning', 'Lunchtime', 'Afternoon', 'Evening', 'Night');

        $builder->add('availability', 'choice',[
            'choices' => $availabilities,
            'label' => 'form.experience.availability',
            'mapped'   => false,
            'expanded' => true,
            'multiple' => false,
            'label' => false,
            'data' => 0,
        ]);

        $builder->add('days', 'choice',[
            'choices' => $days,
            'label' => false,
            'mapped'   => false,
            'expanded' => true,
            'multiple' => true,
            'data' => array("5", "6"),
        ]);

        $builder->add('times', 'choice',[
            'choices' => $times,
            'label' => false,
            'mapped'   => false,
            'expanded' => true,
            'multiple' => true,
        ]);


    }


    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\Experience',
            'translation_domain' => 'experience'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'front_experience_step3';
    }

}