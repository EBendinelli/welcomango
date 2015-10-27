<?php

namespace Welcomango\Bundle\Front\CrmBundle\Form\Type;

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

use Welcomango\Bundle\Front\CrmBundle\Form\Type\SpokenLanguageType;

use Welcomango\Model\User;

/**
 * UserType Form class
 */
class UserType extends AbstractType
{
    /**
     * @var SecurityContextInterface security context
     */
    private $securityContext;

    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    /**
     * __construct
     *
     * @param SecurityContextInterface $securityContext
     * @param EntityManager            $entityManager
     */
    public function __construct(SecurityContextInterface $securityContext, EntityManager $entityManager)
    {
        $this->securityContext = $securityContext;
        $this->entityManager   = $entityManager;
    }

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

        $genders = array(
            'M' => 'M',
            'F' => 'F',
            'O' => 'O'
        );

        $builder->add('username', 'text', ['label' => 'form.user.username']);
        $builder->add('firstName', 'text', ['label' => 'form.user.firstname']);
        $builder->add('lastName', 'text', ['label' => 'form.user.lastname']);
        $builder->add('phone', 'text', ['label' => 'form.user.phone', 'required' => false]);
        $builder->add('occupation', 'text', ['label' => 'form.user.occupation']);
        $builder->add('birthdate', 'date', [
            'years' => range(date('Y') - 100, date('Y') - 10),
            'label' => 'form.user.birthdate',
            'required' => false
        ]);

        $builder->add('description', 'textarea', [
            'label' => 'form.user.description'
        ]);


        /*$builder->add('spokenLanguages', 'collection', array(
            'type'         => new SpokenLanguageType(),
            'allow_add'    => true,
            'allow_delete' => true,
            'by_reference' => false,
        ));*/


        $builder->add('from_city', 'entity', array(
            'class' => 'Model:City',
            'property' => 'name',
        ));

        $builder->add('current_city', 'entity', array(
            'class' => 'Model:City',
            'property' => 'name',
        ));

        $builder->add('gender','choice', array(
            'choices' => $genders,
            'multiple' => false,
            'label' => 'form.user.gender'
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
            'roles_user'         => null,
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
