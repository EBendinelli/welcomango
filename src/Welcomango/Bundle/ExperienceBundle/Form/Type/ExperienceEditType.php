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

        $availabilities = array('form.experience.alwaysAvailable', 'form.experience.specificAvailability');
        $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $hours = array('Early Morning', 'Morning', 'Lunchtime', 'Afternoon', 'Evening', 'Night');
        $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

        $builder->add('availability', 'choice',[
            'choices' => $availabilities,
            'label' => 'form.experience.availability',
            'mapped'   => false,
            'expanded' => true,
            'multiple' => false,
            'label' => false,
            'data' => 0,
        ]);

        $builder->add('day', 'choice',[
            'choices' => $days,
            'label' => false,
            'mapped'   => false,
            'expanded' => true,
            'multiple' => true,
            'data' => array("5", "6"),
        ]);

        $builder->add('hour', 'choice',[
            'choices' => $hours,
            'label' => false,
            'mapped'   => false,
            'expanded' => true,
            'multiple' => true,
        ]);

        /*$builder->add('month', 'choice',[
            'choices' => $months,
            'label' => false,
            'mapped'   => false,
            'expanded' => true,
            'multiple' => true,
        ]);*/

        $builder->add('start_date', 'date', [
            'label'    => 'form.experience.startDate',
            'data'     => new \DateTime(),
            'required' => false,
            'mapped'   => false,
            'years'    => range(date('Y'), date('Y') + 1),
            'months'   => range(date('m'), 12),
            'days'     => range(date('d'), 31),
            'widget'   => 'single_text',
            'format'   => 'dd-MM-yyyy',
            'attr'     => [
                'class'            => 'form-control input-inline datepicker',
                'data-provide'     => 'datepicker',
                'data-date-format' => 'dd-mm-yyyy',
            ],
        ]);

        $builder->add('end_date', 'date', [
            'label'    => 'form.experience.endDate',
            'data'     => new \DateTime(),
            'required' => false,
            'mapped'   => false,
            'years'    => range(date('Y'), date('Y') + 1),
            'months'   => range(date('m'), 12),
            'days'     => range(date('d'), 31),
            'widget'   => 'single_text',
            'format'   => 'dd-MM-yyyy',
            'attr'     => [
                'class'            => 'form-control input-inline datepicker',
                'data-provide'     => 'datepicker',
                'data-date-format' => 'dd-mm-yyyy',
            ],
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

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

    public function getParent(){
        return new ExperienceType($this->securityContext,$this->entityManager);
    }
}
