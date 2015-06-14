<?php

namespace Welcomango\Bundle\CrmBundle\Form\Type;

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

        $builder->add('email', 'text', ['label' => 'form.user.email',]);
        $builder->add('firstName', 'text', ['label' => 'form.user.firstname']);
        $builder->add('lastName', 'text', ['label' => 'form.user.lastname']);
        $builder->add('phone', 'text', ['label' => 'form.user.phone', 'required' => false]);

        $builder->add('email', 'choice', array(
            'choices'  => array('m' => 'Masculin', 'f' => 'Féminin'),
            'required' => false,
        ));

        $builder->add('roles', 'choice', [
            'label'    => 'form.user.roles',
            'required' => false,
            'choices'  => $roles,
            'multiple' => true,
        ]);

        /*

        $builder->add('email', 'welcomango_select2_entity', [
            'label'    => 'form.user.groups',
            'class'    => 'Welcomango\Model\User',
            'multiple' => true,
            'required' => false
        ]);


        $builder->add('enabled', 'checkbox', [
            'label'    => 'form.site_customer.enabled',
            'required' => false
        ]);*/

        /*        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($companyRoles) {
                    if (isset($data['roles'])) {
                        $data       = $event->getData();
                        $finalRoles = array();

                        foreach ($data['roles'] as $role) {
                            if (!in_array($role, $companyRoles)) {
                                $finalRoles[] = $role;
                            }
                        }
                        $data['roles'] = $finalRoles;
                        $event->setData($data);
                    }
                });*/
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        /*        $companyRoles = $form->getData()->getCompany()->getDefaultRoles();

                foreach ($view->children['roles']->children as $role) {
                    if (in_array($role->vars['value'], $companyRoles)) {
                        $role->vars['attr']['disabled'] = 'disabled';
                        $role->vars['checked']          = true;
                    }
                }*/
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
