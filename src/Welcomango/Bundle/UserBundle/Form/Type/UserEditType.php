<?php

namespace Welcomango\Bundle\UserBundle\Form\Type;

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

use Welcomango\Bundle\UserBundle\Form\Type\AdminSpokenLanguageType;

use Welcomango\Model\User;

/**
 * UserEditType Form class
 */
class UserEditType extends AbstractType
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
     * @var array
     */
    protected $levels;

    /**
     * __construct
     *
     * @param SecurityContextInterface $securityContext
     * @param EntityManager            $entityManager
     * @param array                    $levels
     */
    public function __construct(SecurityContextInterface $securityContext, EntityManager $entityManager, $levels)
    {
        $this->securityContext = $securityContext;
        $this->entityManager   = $entityManager;
        $this->levels          = $levels;
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

        $user     = $this->securityContext->getToken()->getUser();
        $userCity = $user->getCurrentCity();

        $genders = array(
            'M' => 'M',
            'F' => 'F',
            'O' => 'O',
        );

        $builder->add('username', 'text', ['label' => 'form.user.username']);
        $builder->add('firstName', 'text', ['label' => 'form.user.firstname']);
        $builder->add('lastName', 'text', ['label' => 'form.user.lastname']);
        $builder->add('phone', 'text', ['label' => 'form.user.phone', 'required' => false]);
        $builder->add('occupation', 'text', ['label' => 'form.user.occupation']);
        $builder->add('birthdate', 'date', [
            'years'    => range(date('Y') - 100, date('Y') - 10),
            'label'    => 'form.user.birthdate',
            'required' => false,
        ]);

        $builder->add('email', 'email', [
            'label'    => 'form.user.email',
            'disabled'  => true,
        ]);

        $builder->add('description', 'textarea', [
            'label'    => 'form.user.description',
            'required' => false,
        ]);

        $builder->add('spokenLanguages', 'collection', array(
            'type'         => new AdminSpokenLanguageType($this->levels),
            'allow_add'    => true,
            'allow_delete' => true,
            'by_reference' => false,
            'required'     => false,
        ));

        $builder->add('currentCityInput', 'text', array(
            'mapped' => false,
            'label'  => 'form.user.currentCityInput',
            'data'   => $userCity->getName(),
        ));

        $builder->add('currentCity', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.currentCity',
            'data'   => $userCity->getName(),
        ));

        $builder->add('currentCityLat', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.currentCityLat',
            'data'   => $userCity->getLatitude(),
        ));

        $builder->add('currentCityLng', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.currentCityLng',
            'data'   => $userCity->getLongitude(),
        ));

        $builder->add('currentCityState', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.currentCityState',
            'data'   => $userCity->getState(),
        ));

        $builder->add('currentCityCountry', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.currentCityCountry',
            'data'   => $userCity->getCountry()->getName(),
        ));

        $builder->add('currentCityCountryCode', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.currentCityCountryCode',
            'data'   => $userCity->getCountry()->getCountryCode(),
        ));

        $builder->add('gender', 'choice', array(
            'choices'  => $genders,
            'multiple' => false,
            'label'    => 'form.user.gender',
        ));

        $builder->add('media_photo', 'file', [
            'required' => false,
            'mapped'   => false,
            'label'    => 'form.profile.uploadPhoto',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\User',
            'translation_domain' => 'interface',
            'roles_user'         => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'front_user_edit';
    }
}
