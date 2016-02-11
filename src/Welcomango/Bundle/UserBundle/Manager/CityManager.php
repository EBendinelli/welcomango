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

    public function checkAndCreateNewCity($parentField){
        $city = $parentField->get('city')->getData();
        $lat = $parentField->get('cityLat')->getData();
        $lng = $parentField->get('cityLng')->getData();
        $state = $parentField->get('cityState')->getData();
        $country = $parentField->get('cityCountry')->getData();
        $countryCode = $parentField->get('cityCountryCode')->getData();

        $CityRepo = $this->entityManager->getRepository('Welcomango\Model\City');
        $CountryRepo = $this->entityManager->getRepository('Welcomango\Model\Country');

        //We check if the city and country are in the database and if not we create the entry
        //We can do this since the form fields are autocompleted by Gmaps so we know they are solid
        if($existingCountry = $CountryRepo->findBy(['name' => $country])){
            if($existingCity = $CityRepo->findBy(['name' => $city, 'country' => $existingCountry[0], 'state' => $state])){
                return $existingCity[0];
            }else{
                $newCity = new City();
                $newCity->setCountry($existingCountry[0]);
            }
        }else{
            $newCity = new City();

            //If the country doesn't exist, we create it
            if($existingCountry = $CountryRepo->findBy(['name' => $country])){
                $newCity->setCountry($existingCountry[0]);
            }else{
                $newCountry = new Country();
                $newCountry->setName($country);
                $newCountry->setCountryCode($countryCode);

                $this->entityManager->persist($newCountry);
                $newCity->setCountry($newCountry);
            }
        }
        $newCity->setState($state);
        $newCity->setName($city);
        $newCity->setLatitude($lat);
        $newCity->setLongitude($lng);

        $this->entityManager->persist($newCity);
        $this->entityManager->flush();

        return $newCity;
    }

    public function getCitiesForAutocomplete(){
        //load cities for autocomplete
        $cityRepository = $this->entityManager->getRepository('Welcomango\Model\City');
        $citiesObject = $cityRepository->findAll();
        $cities = array();
        foreach($citiesObject as $city){
            $cities[] = $city->getName();
        }

        return $cities;
    }

}

