<?php

namespace Welcomango\Bundle\UserBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Welcomango\Model\City;
use Welcomango\Model\Country;

class CityManager
{

    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager   = $entityManager;
    }

    public function checkAndCreateNewCity($city, $lat, $lng, $sate, $country, $countryCode){
        $CityRepo = $this->entityManager->getRepository('Welcomango\Model\City');
        $CountryRepo = $this->entityManager->getRepository('Welcomango\Model\Country');

        //We check if the city and country are in the database and if not we create the entry
        //We can do this since the form fields are autocompleted by Gmaps so we know they are solid
        if($existingCity = $CityRepo->findBy(['name' => $city, 'country' => $country, 'state' => $sate])){
            return $existingCity;
        }else{
            $newCity = new City();

            //If the country doesn't exist, same process
            if($existingCountry = $CountryRepo->findBy(['name' => $country])){
                $newCity->setCountry($existingCountry[0]);
            }else{
                $newCountry = new Country();
                $newCountry->setName($country);
                $newCountry->setCountryCode($countryCode);

                $this->entityManager->persist($newCountry);
                $newCity->setCountry($newCountry);
            }

            $newCity->setState($sate);
            $newCity->setName($city);
            $newCity->setLatitude($lat);
            $newCity->setLongitude($lng);

            $this->entityManager->persist($newCity);
        }

        $this->entityManager->flush();

        return $newCity;
    }

}
