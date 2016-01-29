<?php

namespace Welcomango\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * CityType Form class
 */
class CityType extends AbstractType
{


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('cityInput', 'text', array(
            'mapped' => false,
            'label'  => 'form.user.cityInput',
            'attr' => [
                'onFocus' => 'geolocate()'
            ]
        ));

        $builder->add('city', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.city',
        ));

        $builder->add('cityLat', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.cityLat',
        ));

        $builder->add('cityLng', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.cityLng',
        ));

        $builder->add('cityState', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.cityState',
        ));

        $builder->add('cityCountry', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.cityCountry',
        ));

        $builder->add('cityCountryCode', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.cityCountryCode',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\User',
            'translation_domain' => 'interface',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'city';
    }
}
