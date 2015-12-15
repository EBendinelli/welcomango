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
 * AvailabilityType Form class
 */
class AvailabilityType extends AbstractType
{

    /**
     * __construct
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'Always');
        $hours = array('Early Morning', 'Morning', 'Lunchtime', 'Afternoon', 'Evening', 'Night', 'Always');
        /*$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');*/

        $builder->add('day', 'choice',[
            'required' => true,
            'choices' => $days,
            'label' => false,
            'expanded' => true,
            'multiple' => true
        ]);

        $builder->add('hour', 'choice',[
            'choices' => $hours,
            'label' => false,
            'expanded' => true,
            'multiple' => true,
            'required' => true,
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
            'required' => true,
            'data'     => new \Datetime,
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
            'required' => true,
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

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\Availability',
            'translation_domain' => 'availability'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'front_availability_edit';
    }
}
