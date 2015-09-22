<?php

namespace Welcomango\Bundle\Admin\CrmBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

use Welcomango\Model\User;

/**
 * SpokenLanguageType Form class
 */
class SpokenLanguageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = array(
            User::ROLE_SUPER_ADMIN => User::ROLE_SUPER_ADMIN,
            User::ROLE_ADMIN       => User::ROLE_ADMIN,
            User::ROLE_USER        => User::ROLE_USER,
        );

        $builder->add('username', 'text', ['label' => 'form.user.username',]);
        $builder->add('email', 'text', ['label' => 'form.user.email',]);
        $builder->add('firstName', 'text', ['label' => 'form.user.firstname']);
        $builder->add('lastName', 'text', ['label' => 'form.user.lastname']);
        $builder->add('phone', 'text', ['label' => 'form.user.phone', 'required' => false]);

        $builder->add('roles', 'choice', [
            'label'    => 'form.user.roles',
            'required' => false,
            'choices'  => $roles,
            'multiple' => true,
        ]);

        $builder->add('spokenLanguages', 'hidden', array(
            'data' => '',
        ));

        $builder->add('password', 'repeated', array(
            'type'            => 'password',
            'invalid_message' => 'Les mots de passe doivent correspondre',
            'options'         => array('required' => true),
            'first_options'   => array('label' => 'form.user.password'),
            'second_options'  => array('label' => 'form.user.password.validate'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\User',
            'translation_domain' => 'crm',
            'roles_user'         => null
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_user';
    }
}
