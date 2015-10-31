<?php

namespace Welcomango\Bundle\ParticipationBundle\Form\Type;

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

use Welcomango\Model\Participation;
use Welcomango\Model\Experience;
use Welcomango\Model\User;

/**
 * AdminParticipationType Form class
 */
class AdminParticipationType extends AbstractType
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

        $status = array(
            'available' => 'available',
            'booked' => 'booked',
            'happened' =>'happened',
            'requested' => 'requested',
            'validated' => 'validated'
        );
        $numberOfParticipants = array();
        for($i=0;$i<48;$i++) $numberOfParticipants [$i] = $i;

        $builder->add('experience', 'entity', array(
            'class' => 'Model:Experience',
            'property' => 'title',
        ));

        $builder->add('user', 'entity', array(
            'class' => 'Model:User',
            'property' => 'fullName',
        ));

        $builder->add('is_creator', 'checkbox',[
            'label' => 'form.participation.isCreator'
        ]);

        $builder->add('is_participant', 'checkbox',[
            'label' => 'form.participation.isParticipant'
        ]);

        $builder->add('date', 'date', [
            'label' => 'form.participation.date',
            'data' => new \DateTime(),
            'required' => false,
            'years' => range(date('Y'), date('Y') + 1),
            'months' => range(date('m'), 12),
            'days' => range(date('d'), 31),
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
            'attr' => [
                'class' => 'form-control input-inline datepicker',
                'data-provide' => 'datepicker',
                'data-date-format' => 'dd-mm-yyyy'
            ]
        ]);

        $builder->add('start_time', 'time', array(
            'label' => 'form.participation.startTime',
            'input'  => 'datetime',
            'widget' => 'choice',
        ));

        $builder->add('end_time', 'time', array(
            'label' => 'form.participation.endTime',
            'input'  => 'datetime',
            'widget' => 'choice',
        ));


        $builder->add('status', 'choice',[
            'choices' => $status,
            'label' => 'form.participation.status',
        ]);

        $builder->add('number_of_participants', 'choice', [
            'choices' => $numberOfParticipants,
            'label' => 'form.participation.numberOfParticipants'
        ]);



        $builder->add('save', 'submit');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\Participation',
            'translation_domain' => 'participation'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_participation';
    }
}
