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
 * ExperienceEditType Form class
 */
class ExperienceEditType extends AbstractType
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

        $estimatedDurations = array();
        $minimumDurations = array();
        $maximumDurations = array();
        $maximumParticipants = array();
        for($i=0;$i<48;$i++) $estimatedDurations[$i] = $i;
        for($i=0;$i<48;$i++) $minimumDurations[$i] = $i;
        for($i=0;$i<48;$i++) $maximumDurations[$i] = $i;
        for($i=0;$i<10;$i++) $maximumParticipants[$i] = $i;

        $builder->add('title', 'text', ['label' => 'form.experience.title']);
        $builder->add('description', 'textarea', [
            'label' => 'form.experience.description'
        ]);

        $builder->add('city', 'entity', array(
            'class' => 'Model:City',
            'property' => 'name',
            'disabled' => true,
        ));

        $builder->add('estimated_duration', 'choice',[
            'choices' => $estimatedDurations,
            'label' => 'form.experience.estimatedDuration'
        ]);

        $builder->add('tags', 'entity', array(
            'class' => 'Model:Tag',
            'property' => 'name',
            'multiple' => true,
        ));

        $builder->add('minimum_duration', 'choice',[
            'choices' => $minimumDurations,
            'label' => 'form.experience.minimumDuration'
        ]);

        $builder->add('maximum_duration', 'choice', [
            'choices' => $maximumDurations,
            'label' => 'form.experience.maximumDuration'
        ]);

        $builder->add('price_per_hour', 'text', ['label' => 'form.experience.pricePerHour']);

        $builder->add('maximum_participants', 'choice', [
            'choices' => $maximumParticipants,
            'label' => 'form.experience.maximumParticipants'
        ]);

        $builder->add('availabilities', 'collection', [
            'type'         => new AvailabilityType(),
            'allow_add'    => true,
            'by_reference' => false,
            'allow_delete' => true,
            'label'        => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\Experience',
            'translation_domain' => 'experience'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'front_experience_edit';
    }
}
