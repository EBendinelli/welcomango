<?php

namespace Welcomango\Bundle\ExperienceBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Welcomango\Bundle\CoreBundle\DataTransformer\CityTransformer;
use Welcomango\Model\Experience;

/**
 * ExperienceHomeType form
 */
class ExperienceHomeType extends AbstractType
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
        $builder
            ->add('city', TextType::class, [
                'required' => false,
                'label'    => 'form.city',
                'attr'     => [
                    'placeholder' => 'Where do you wanna go...',
                ],
            ]);

        $builder->add('date', 'date', [
            'label'    => 'form.experience.date',
            'required' => false,
            'years'    => range(date('Y'), date('Y') + 1),
            'months'   => range(date('m'), 12),
            'days'     => range(date('d'), 31),
            'widget'   => 'single_text',
            'format'   => 'dd-MM-yyyy',
            'attr'     => [
                'class'            => 'form-control input-inline datepicker',
                'data-provide'     => 'datepicker',
                'data-date-format' => 'dd-mm-yyyy',
                'placeholder'      => 'and when ?',
            ],
        ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'interface',
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'experience_home_research';
    }
}