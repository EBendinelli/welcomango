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
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

use Welcomango\Model\Booking;
use Welcomango\Model\Experience;

/**
 * BookingType Form class
 */
class BookingType extends AbstractType
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
        $desiredDuration      = array();
        $numberOfParticipants = array();
        $experience = $options['experience'];
        $forbiddenDates = $options['forbiddenDates'];

        //We check the available date to setup the selected date per default
        $interval       = \DateInterval::createFromDateString('1 day');
        $defaultDate= new \DateTime();
        $endDate = new \DateTime();
        $endDate = $endDate->modify('+1 year');
        $period = new \DatePeriod(new \DateTime(), $interval, $endDate);
        foreach ($period as $day) {
            if (!in_array($day->format('Y-m-d'), $forbiddenDates)) {
                $defaultDate = $day;
                break;
            }
        }

        for ($i = $experience->getMinimumDuration(); $i <= $experience->getMaximumDuration(); $i++) {
            $desiredDuration[$i] = $i.':00';
        }

        for ($i = 1; $i < $experience->getMaximumParticipants()+1; $i++) {
            $numberOfParticipants[$i] = $i;
        }

        $desiredTime = $options['meeting_times'];

        $builder->add('message', 'textarea', [
            'label'  => 'form.booking.message',
            'mapped' => false,
        ]);

        $builder->add('desired_duration', 'choice', [
            'choices' => $desiredDuration,
            'label'   => 'form.booking.desiredDuration',
            'mapped'  => false,
            'data'    => '1',
        ]);

        $builder->add('desired_date', 'date', [
            'label'    => 'form.booking.desiredDate',
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
            'data'     => $defaultDate,
        ]);

        $builder->add('desired_time', 'choice', [
            'choices'  => $desiredTime,
            'label'    => 'form.booking.desiredTime',
            'required' => false,
            'mapped'   => false,
            'placeholder' => false,
            'data'     => '1',
        ]);

        $builder->add('number_of_participants', 'choice', [
            'choices' => $numberOfParticipants,
            'label'   => 'form.booking.numberOfParticipants',
            'data'    => '1',
        ]);

        $builder->add('submit', 'submit');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\Booking',
            'translation_domain' => 'interface',
        ]);
        $resolver->setRequired(array('available_status', 'meeting_times', 'experience', 'forbiddenDates'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'front_booking';
    }
}
