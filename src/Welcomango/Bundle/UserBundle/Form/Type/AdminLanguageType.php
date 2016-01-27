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

/**
 * AdminLanguageType Form class
 */
class AdminLanguageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language', 'text', [
                'label'    => 'form.language.language',
                'required' => true,
            ])
            ->add('languageCode', 'text', [
                'label'    => 'form.user.language_code',
                'required' => true,
            ])
            ->add('flagLabel', 'text', [
                'label'    => 'form.user.flag_label',
                'required' => true,
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\Language',
            'translation_domain' => 'interface',
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
