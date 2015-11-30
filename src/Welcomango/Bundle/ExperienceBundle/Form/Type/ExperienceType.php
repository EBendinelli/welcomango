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

        $availabilities = array('form.experience.alwaysAvailable', 'form.experience.specificAvailability');
        $days           = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $hours          = array('Early Morning', 'Morning', 'Lunchtime', 'Afternoon', 'Evening', 'Night');
        $months         = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

        switch ($options['flow_step']) {
            case 1:
                $builder
                    ->add('title', 'text', ['label' => 'form.experience.title'])
                    ->add('description', 'textarea', ['label' => 'form.experience.description'])
                    ->add('city', 'entity', array(
                        'class'    => 'Model:City',
                        'property' => 'name',
                    ))
                    ->add('tags', 'entity', array(
                        'class'    => 'Model:Tag',
                        'property' => 'name',
                        'multiple' => true,
                    ))
                    ->add('medias', 'file', array(
                        'multiple' => true,
                        'mapped'   => false,
                        'required' => false,
                    ))
                ;

                break;
            case 2:
                $builder
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
                    ->add('price_per_hour', 'integer', [
                        'label' => 'form.experience.pricePerHour',
                        'attr'    => array(
                            'min'   => '0',
                            'max'   => '400',
                        )
                    ])
                    ->add('maximum_participants', 'choice', [
                        'choices' => $maximumParticipants,
                        'label'   => 'form.experience.maximumParticipants',
                    ])
                ;

                break;
            case 3:
                $builder->add('availabilities', 'collection', [
                    'type' => new AvailabilityType(),
                    'allow_add'    => true,
                    'by_reference' => false,
                    'allow_delete' => true,
                    'label'        => false,
                ]);
                break;
        }

        $builder->add('medias_id', 'hidden', [
            'mapped' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\Experience',
            'translation_domain' => 'experience',
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
