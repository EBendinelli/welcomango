<?php

namespace Welcomango\Bundle\ExperienceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Welcomango\Model\Country;

/**
 * Class LoadCountryData
 */
class LoadCountryData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $countries = array_map('str_getcsv', file(__DIR__.'/../../../../Data/GeodataSource_coutries.csv'));

        foreach ($countries as $country) {
            $entry = new Country();
            $entry->setName($country[1]);
            $entry->setCountryCode($country[0]);

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
        return 1;
    }
}
