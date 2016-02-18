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
use Symfony\Component\Validator\Constraints\Email;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

use Welcomango\Bundle\CoreBundle\Form\Type\CityType;
use Welcomango\Bundle\UserBundle\Form\Type\AdminSpokenLanguageType;

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

        $genders = array(
            'M' => 'M',
            'F' => 'F',
            'O' => 'O',
        );

        $builder->add('username', 'text', ['label' => 'form.user.username']);
        $builder->add('firstName', 'text', ['label' => 'form.user.firstname']);
        $builder->add('lastName', 'text', ['label' => 'form.user.lastname']);
        $builder->add('email', 'email', ['label' => 'form.user.email', 'constraints' => [new Email()]]);
        $builder->add('occupation', 'text', ['label' => 'form.user.occupation']);

        $builder->add('birthdate', 'date', [
            'years'    => range(date('Y') - 100, date('Y') - 10),
            'label'    => 'form.user.birthdate',
            'required' => false,
            'data'     => new \DateTime('1990-03-01 17:26:30'),
        ]);

        $builder->add('plain_password', 'repeated', array(
            'type'            => 'password',
            'invalid_message' => 'The passwords don\'t match',
            'options'         => array('required' => true),
            'first_options'   => array('label' => 'form.user.password'),
            'second_options'  => array('label' => 'form.user.password.validate'),
        ));

        $builder->add('currentCity', new CityType(null, 'currentCity'));

        $builder->add('fromCity', new CityType(null, 'fromCity'));

        $builder->add('gender', 'choice', array(
            'choices'  => $genders,
            'multiple' => false,
            'label'    => 'form.user.gender',
        ));

        $builder->add('captcha', 'ewz_recaptcha', array(
            'constraints' => new RecaptchaTrue(),
            'mapped'      => false,
        ));

        $builder->add('register', 'submit');
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
        return 'front_user';
    }
}
