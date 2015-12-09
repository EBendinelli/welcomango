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

/**
 * RatingCommentType Form class
 */
class RatingCommentType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $note = array();
        for($i = 1;$i<=5; $i++) $note[] = $i;

        $builder
            ->add('body', 'textarea', [
                'label' => 'form.rating.comment',
                'required' => true,
            ])
            ->add('note', 'choice', [
                'label'    => 'form.rating.note',
                'choices'  => $note,
                'expanded' => true,
                'required' => true,
                'label_attr' => array(
                    'class' => 'radio-inline'
                )
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'booking',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'front_rating_comment';
    }
}
