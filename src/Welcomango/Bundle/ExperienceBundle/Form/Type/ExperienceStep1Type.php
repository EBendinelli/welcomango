<?php

namespace Welcomango\Bundle\ExperienceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Welcomango\Model\Experience;
use Welcomango\Model\City;

/**
 * ExperienceStep1Type Form class
 */
class ExperienceStep1Type extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('title', 'text', ['label' => 'form.experience.title']);
        $builder->add('description', 'textarea', [
            'label' => 'form.experience.description'
        ]);

        $builder->add('city', 'entity', array(
            'class' => 'Model:City',
            'property' => 'name',
        ));
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
        return 'front_experience_step1';
    }

}