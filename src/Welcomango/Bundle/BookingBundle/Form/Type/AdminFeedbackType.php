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
use Welcomango\Model\Feedback;

/**
 * AdminFeedbackType Form class
 */
class AdminFeedbackType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $note = array();
        for($i=1; $i<6; $i++) $note[] = $i;

        $builder->add('booking', 'entity', array(
            'class' => 'Model:Booking',
            'property' => 'id',
        ));

        $builder->add('sender', 'entity', array(
            'class' => 'Model:User',
            'property' => 'fullName',
        ));

        $builder->add('receiver', 'entity', array(
            'class' => 'Model:User',
            'property' => 'fullName',
        ));


        $builder->add('note', 'choice',[
            'choices' => $note,
            'label' => 'form.feedback.note',
            'expanded' => true,
            'label_attr' => array(
                'class' => 'radio-inline'
            )
        ]);

        $builder->add('comment', 'textarea', [
            'label' => 'form.feedback.comment'
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\Feedback',
            'translation_domain' => 'feedback'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_feedback';
    }
}
