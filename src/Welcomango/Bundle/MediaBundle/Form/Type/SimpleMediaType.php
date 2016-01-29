<?php

namespace Welcomango\Bundle\MediaBundle\Form\Type;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * SimpleMediaType Form class
 */
class SimpleMediaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('media_photo', 'file', [
            'required' => false,
            'mapped'   => false,
            'label'    => 'form.profile.uploadPhoto',
        ]);

        $builder->add('originalFilename', HiddenType::class, [
            'required' => false,
            'label'    => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Welcomango\Model\Media',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'simple_media_form';
    }
}
