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
     * @var array
     */
    private $city;

    private $labelName;

    function __construct($city = null, $labelName = '')
    {
        $this->city = $city;
        $this->labelName = $labelName;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $city = ''; $lat = ''; $lng = ''; $state =''; $country = ''; $countryCode ='';
        if($this->city){
            $city = $this->city->getName();
            $lat = $this->city->getLatitude();
            $lng = $this->city->getLongitude();
            $state = $this->city->getState();
            $country = $this->city->getCountry()->getName();
            $countryCode = $this->city->getCountry()->getCountryCode();
        }

        $label = 'form.user.cityInput';
        if($this->labelName){
            $label = 'form.user.'.$this->labelName;
        }

        $builder->add('cityInput', 'text', array(
            'mapped' => false,
            'label'  => $label,
            'attr' => [
                'onFocus' => 'geolocate()'
            ],
            'data' => $city,
        ));

        $builder->add('city', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.city',
            'data' => $city,
        ));

        $builder->add('cityLat', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.cityLat',
            'data' => $lat,
        ));

        $builder->add('cityLng', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.cityLng',
            'data' => $lng,
        ));

        $builder->add('cityState', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.cityState',
            'data' => $state,
        ));

        $builder->add('cityCountry', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.cityCountry',
            'data' => $country,
        ));

        $builder->add('cityCountryCode', 'hidden', array(
            'mapped' => false,
            'label'  => 'form.user.cityCountryCode',
            'data' => $countryCode,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\City',
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
