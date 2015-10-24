<?php

namespace Welcomango\Bundle\Admin\ExperienceBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Welcomango\Model\Experience;
use Welcomango\Model\City;

/**
 * KeywordType form
 */
class ExperienceFilterType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', 'entity', [
                'label'    => 'form.experience.city',
                'required' => false,
                'class' => 'Model:City',
                'multiple' => true,
                'property' => 'name',
            ])
            ->add('title', 'text', [
                'required' => false,
                'label'    => 'experience.title',
            ])
            ->add('description', 'text', [
                'required' => false,
                'label'    => 'experience.description',
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
