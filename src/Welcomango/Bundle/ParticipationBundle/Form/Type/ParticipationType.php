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

/**
 * ParticipationType Form class
 */
class ParticipationType extends AbstractType
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
        $desiredTime          = array();

        for ($i = 0; $i < 48; $i++) {
            $desiredDuration[$i] = $i;
        }

        for ($i = 0; $i < 20; $i++) {
            $numberOfParticipants[$i] = $i;
        }

        $desiredTime[] = "Morning";
        $desiredTime[] = "Lunchtime";
        $desiredTime[] = "Afternoon";
        $desiredTime[] = "Evening";

        $builder->add('message', 'textarea', [
            'label'  => 'form.participation.message',
            'mapped' => false,
        ]);

        $builder->add('desired_duration', 'choice', [
            'choices' => $desiredDuration,
            'label'   => 'form.participation.desiredDuration',
            'mapped'  => false,
            'data'    => '1',
        ]);

        $builder->add('desired_date', 'date', [
            'label'    => 'form.participation.desiredDate',
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

        $builder->add('desired_time', 'choice', [
            'choices'  => $desiredTime,
            'label'    => 'form.participation.desiredTime',
            'required' => false,
            'mapped'   => false,
            'data'     => '1',
        ]);

        $builder->add('number_of_participants', 'choice', [
            'choices' => $numberOfParticipants,
            'label'   => 'form.participation.numberOfParticipants',
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
            'data_class'         => 'Welcomango\Model\Participation',
            'translation_domain' => 'participation',
        ]);
        $resolver->setRequired('available_status');

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'front_participation';
    }
}
