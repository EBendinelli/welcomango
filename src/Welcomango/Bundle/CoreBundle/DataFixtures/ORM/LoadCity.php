<?php

namespace Welcomango\Bundle\CoreBundle\DataFixtures\ORM;

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
            array('name' => 'Lyon', 'state' => 'RA', 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'France'))),
            array('name' => 'Paris', 'state' => 'test', 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'France'))),
            array('name' => 'Helsinki', 'state' => 'test', 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Finland'))),
            array('name' => 'Brussels', 'state' => 'test', 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Belgium'))),
            array('name' => 'London', 'state' => 'test', 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'United Kingdom'))),
            array('name' => 'Rome', 'state' => 'test', 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Italy'))),
            array('name' => 'Milan', 'state' => 'test', 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Italy'))),
            array('name' => 'Barcelone', 'state' => 'test', 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Spain'))),
            array('name' => 'Madrid', 'state' => 'test', 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Spain'))),
            array('name' => 'Vienna', 'state' => 'test', 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Austria'))),
            array('name' => 'Amsterdam', 'state' => 'test', 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Netherlands'))),
            array('name' => 'Delft', 'state' => 'test', 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Netherlands'))),
            array('name' => 'Geneva', 'state' => 'test', 'geolocation' => '65484468', 'country' => $countryRepo->findOneBy(array('name' => 'Switzerland'))),
            array('name' => 'Strasbourg', 'state' => 'test', 'geolocation' => '688468', 'country' => $countryRepo->findOneBy(array('name' => 'France')))
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
            $entry->setState($city['state']);
            $entry->setLatitude($city['geolocation']);
            $entry->setLongitude($city['geolocation']);

            //ld($city);
            $country = $city['country'];
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
        return 2;
    }
}
