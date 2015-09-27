<?php

namespace Welcomango\Bundle\Admin\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->add('title', 'text', [
                'label'    => 'form.language.language',
                'required' => true,
            ])
            ->add('description', 'text', [
                'label'    => 'form.user.language_code',
                'required' => true,
            ])
            ->add('file', 'file', [
                'label'    => 'form.user.flag_label',
                'required' => true,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\Media',
            'translation_domain' => 'media',
            'roles_user'         => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_language';
    }
}