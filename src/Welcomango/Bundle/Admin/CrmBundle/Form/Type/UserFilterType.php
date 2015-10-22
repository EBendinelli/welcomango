<?php

namespace Welcomango\Bundle\Admin\CrmBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Welcomango\Model\User;

/**
 * KeywordType form
 */
class UserFilterType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', 'choice', [
                'label'    => 'form.user.roles',
                'required' => false,
                'choices'  => User::getAvailableRoles(),
                'multiple' => true,
            ])
            ->add('username', 'text', [
                'required' => false,
                'label'    => 'user.username',
            ])
            ->add('enabled', 'yes_no', [
                'required' => false,
                'label'    => 'user.is_active',
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'crm',
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'user_research';
    }
}
