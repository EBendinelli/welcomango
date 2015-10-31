<?php

namespace Welcomango\Bundle\Common\BaseBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Welcomango\Model\User;

/**
 * MarkType Form class
 */
class MarkType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => 'form.type.mark',
            'data' => '1',
            'attr'  => array(
                'class'         => 'star',
                'min'        => 1,
                'max'        => 5,
                'selected'   => 'fa-star',
                'unselected' => 'fa-star-o',
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'number';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mark';
    }
}
