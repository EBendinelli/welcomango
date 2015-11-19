<?php

namespace Welcomango\Bundle\BookingBundle\Form\Type;

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

use Welcomango\Model\Booking;
use Welcomango\Model\Experience;
use Welcomango\Model\User;

/**
 * AdminBookingType Form class
 */
class AdminBookingType extends AbstractType
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
        $status = array_combine($options['available_status'], $options['available_status']);

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

        /*$builder->add('date', 'date', [
            'label' => 'form.booking.date',
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
        ]);*/

        $builder->add('start_datetime', 'time', array(
            'label' => 'form.booking.startDatetime',
            'input'  => 'datetime',
            'widget' => 'choice',
        ));

        $builder->add('end_datetime', 'time', array(
            'label' => 'form.booking.endDatetime',
            'input'  => 'datetime',
            'widget' => 'choice',
        ));


        $builder->add('status', 'choice',[
            'choices' => $status,
            'label' => 'form.booking.status',
        ]);

        $builder->add('number_of_participants', 'choice', [
            'choices' => $numberOfParticipants,
            'label' => 'form.booking.numberOfParticipants'
        ]);

        $builder->add('save', 'submit');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\Booking',
            'translation_domain' => 'booking'
        ]);
        $resolver->setRequired('available_status');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_booking';
    }
}
