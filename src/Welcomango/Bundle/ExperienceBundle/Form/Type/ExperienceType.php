<?php

namespace Welcomango\Bundle\ExperienceBundle\Form\Type;

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
use Welcomango\Bundle\MediaBundle\Form\Type\MediaType;
use Welcomango\Bundle\CoreBundle\Form\Type\CityType;

use Welcomango\Model\Experience;
use Welcomango\Model\City;

/**
 * ExperienceType Form class
 */
class ExperienceType extends AbstractType
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
        $estimatedDurations  = array();
        $minimumDurations    = array();
        $maximumDurations    = array();
        $maximumParticipants = array();
        for ($i = 1; $i < 48; $i++) $estimatedDurations[$i] = $i;
        for ($i = 1; $i < 48; $i++) $minimumDurations[$i] = $i;
        for ($i = 1; $i < 48; $i++) $maximumDurations[$i] = $i;
        for ($i = 1; $i < 10; $i++) $maximumParticipants[$i] = $i;

        $builder
            ->add('title', 'text', ['label' => 'form.experience.title'])
            ->add('description', 'textarea', ['label' => 'form.experience.description'])
            ->add('tags', 'entity', array(
                'class'    => 'Model:Tag',
                'choice_label'  => 'name',
                'choice_translation_domain' => 'interface',
                'multiple' => true,
            ))
            ->add('medias', 'collection', [
                'type'         => new MediaType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'label'        => false,
                'by_reference' => false,
            ])
            ->add('estimated_duration', 'choice', [
                'choices' => $estimatedDurations,
                'label'   => 'form.experience.estimatedDuration',
            ])
            ->add('minimum_duration', 'choice', [
                'choices' => $minimumDurations,
                'label'   => 'form.experience.minimumDuration',
            ])
            ->add('maximum_duration', 'choice', [
                'choices' => $maximumDurations,
                'label'   => 'form.experience.maximumDuration',
            ])
            ->add('price_per_hour', 'integer', ['label' => 'form.experience.pricePerHour'])
            ->add('currency', 'entity', [
                'class' => 'Model:Currency',
                'property' => 'name',
                'label' => 'form.experience.currency'
            ])
            ->add('maximum_participants', 'choice', [
                'choices' => $maximumParticipants,
                'label'   => 'form.experience.maximumParticipants',
            ])
            ->add('availabilities', 'collection', [
                'type'         => new AvailabilityType(),
                'allow_add'    => true,
                'by_reference' => false,
                'allow_delete' => true,
                'label'        => false,
            ])
            ->add('contribution', 'text', [
                'label' => 'form.experience.contribution',
                'required' => false,
            ]);

        //Add the city field only for creation
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $experience = $event->getData();
            $form = $event->getForm();

            if (!$experience || null === $experience->getId()) {
                $form->add('city', new CityType(), [
                    'required' => false,
                ]);
            }
        });

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\Experience',
            'translation_domain' => 'interface',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'front_experience';
    }
}
