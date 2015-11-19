<?php

namespace Welcomango\Bundle\ExperienceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Welcomango\Model\Experience;

/**
 * ExperienceStep1Type Form class
 */
class ExperienceStep2Type extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $estimatedDurations = array();
        $minimumDurations = array();
        $maximumDurations = array();
        $maximumParticipants = array();
        for($i=0;$i<48;$i++) $estimatedDurations[$i] = $i;
        for($i=0;$i<48;$i++) $minimumDurations[$i] = $i;
        for($i=0;$i<48;$i++) $maximumDurations[$i] = $i;
        for($i=0;$i<10;$i++) $maximumParticipants[$i] = $i;

        $builder->add('estimated_duration', 'choice',[
            'choices' => $estimatedDurations,
            'label' => 'form.experience.estimatedDuration'
        ]);

        $builder->add('minimum_duration', 'choice',[
            'choices' => $minimumDurations,
            'label' => 'form.experience.minimumDuration'
        ]);

        $builder->add('maximum_duration', 'choice', [
            'choices' => $maximumDurations,
            'label' => 'form.experience.maximumDuration'
        ]);

        $builder->add('price_per_hour', 'text', ['label' => 'form.experience.pricePerHour']);

        $builder->add('maximum_participants', 'choice', [
            'choices' => $maximumParticipants,
            'label' => 'form.experience.maximumParticipants'
        ]);

        $builder->add('maximum_participants', 'choice', [
            'choices' => $maximumParticipants,
            'label' => 'form.experience.maximumParticipants'
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
        return 'front_experience_step2';
    }

}