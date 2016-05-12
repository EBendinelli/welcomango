<?php

namespace Welcomango\Bundle\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * MediaType Form class
 */
class MediaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('originalFilename', HiddenType::class, [
                'required' => false,
                'label'    => false,
            ])

            ->add('default', HiddenType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'class' => 'defaultImageInput',
                ),
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
        return 'media_form';
    }
}
