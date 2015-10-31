<?php

namespace Welcomango\Bundle\UserBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Welcomango\Bundle\UserBundle\Form\DataTransformer\CityTransformer;
use Welcomango\Model\User;

/**
 * AdminUserFilterType form
 */
class AdminUserFilterType extends AbstractType
{
    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    /**
     * __construct
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new CityTransformer($this->entityManager);

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
            ->add($builder->create('city', 'genemu_jqueryselect2_hidden', [
                'configs' => [],
                'label'   => 'form.city',
            ])->addModelTransformer($transformer))
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
