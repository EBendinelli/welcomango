<?php

namespace Welcomango\Bundle\UserBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
 * AdminSpokenLanguageType Form class
 */
class AdminSpokenLanguageType extends AbstractType
{
    /**
     * @var array
     */
    private $levels;

    function __construct($levels)
    {
        $this->levels = $levels;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language', 'entity', [
                'label'         => 'form.user.languages',
                'choice_label'      => 'language',
                'choice_translation_domain' => 'interface',
                'class'         => 'Welcomango\Model\Language',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l');
                },
            ])
            ->add('level', ChoiceType::class, [
                'label'    => 'form.user.level',
                'required' => true,
                'choices'  => $this->levels,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\SpokenLanguage',
            'translation_domain' => 'interface',
            'roles_user'         => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_spoken_language';
    }
}
