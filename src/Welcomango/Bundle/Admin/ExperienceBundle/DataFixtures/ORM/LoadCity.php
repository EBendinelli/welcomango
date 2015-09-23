<?php

namespace Welcomango\Bundle\Admin\ExperienceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Welcomango\Model\City;

class LoadCityData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $countryRepo = $manager->getRepository('Welcomango\Model\Country');

        $cities = array(
            array('name' => 'Lyon', 'postcode' => 69001, 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'France'))),
            array('name' => 'Paris', 'postcode' => 75001, 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'France'))),
            array('name' => 'Helsinki', 'postcode' => 65450, 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Finland'))),
            array('name' => 'Brussels', 'postcode' => 1000, 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Belgium'))),
            array('name' => 'London', 'postcode' => 2100, 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'England'))),
            array('name' => 'Rome', 'postcode' => 5486, 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Italy'))),
            array('name' => 'Milan', 'postcode' => 4700, 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Italy'))),
            array('name' => 'Barcelone', 'postcode' => 2005, 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Spain'))),
            array('name' => 'Madrid', 'postcode' => 200010, 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Spain'))),
            array('name' => 'Vienna', 'postcode' => 780000, 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Austria'))),
            array('name' => 'Amsterdam', 'postcode' => 6544, 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Netherlands'))),
            array('name' => 'Delft', 'postcode' => 4400, 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Netherlands'))),
            array('name' => 'Geneva', 'postcode' => 4400, 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Switzerland')))
        );

        //$cities = array_map('str_getcsv', file('/home/eliot/www/welcomango/src/Welcomango/Data/newfile.csv'));

        /*$countries = $countryRepo->findAll();
        $country_codes = array();
        foreach($countries as $country){
            $country_codes[] = $country->getCountryCode();
        }

        $newFile = fopen("/home/eliot/www/welcomango/src/Welcomango/Data/newfile.csv", "w") or die("Unable to open file!");
        $i=0;
        foreach($cities as $city){
            if(in_array($city[0],$country_codes)){
                $i++;
                fwrite($newFile, $city[0].','.$city[1]."\r\n");
            }
        }
        fclose($newFile);*/

        foreach($cities as $city){
            $entry = new City();
            $entry->setName($city['name']);
            $entry->setPostcode('00000');
            $entry->setGeolocation('00000');

            //ld($city);
            $country = $countryRepo->findOneBy(array('countryCode' => $city['country']));
            $entry->setCountry($country);

            $manager->persist($entry);
        }
        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        //Define the order in which the fixtures are executed
        return 5;
    }
}
