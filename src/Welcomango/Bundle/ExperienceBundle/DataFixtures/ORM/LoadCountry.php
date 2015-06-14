<?php

namespace Welcomango\Bundle\ExperienceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Welcomango\Model\Country;

class LoadCountryData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $countries = array('France','England','Germany','Italy','Spain','Belgium','Austria','Finland','Netherlands','Switzerland');


        foreach($countries as $country){
            $entry = new Country();
            $entry->setName($country);
            $code = \strtoupper(\substr($country, 0, 2));
            $entry->setCountryCode($code);

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
        return 4;
    }
}