<?php

namespace Welcomango\Bundle\MediaBundle\Form\Type;

use Proxies\__CG__\Welcomango\Model\Media;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Tests\Fixtures\Entity;

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
        $builder->add('originalFilename', HiddenType::class, [
            'required' => false,
            'label'    => false,
        ])
        ->add('originalFile', EntityType::class, [
            'required' => false,
            'label'    => false,
            'class'    => Media,
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
