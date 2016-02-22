<?php

namespace Welcomango\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Welcomango\Model\Currency;

class LoadCurrencyData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $currencies = array_map('str_getcsv', file(__DIR__.'/../../../../Data/currencies.csv'));

        foreach($currencies as $k => $currency){
            $entry = new Currency();
            $entry->setName($currency[2]);
            $entry->setCode($currency[1]);
            $entry->setSymbol($currency[0]);
            $entry->setRate($currency[3]);
            $entry->setFormat($currency[4]);
            $entry->setPosition($currency[5]);
            $entry->setCreatedAt(new \DateTime());
            $entry->setUpdatedAt(new \DateTime());
            if($k<3){
                $entry->setPublished(1);
            }else{
                $entry->setPublished(0);
            }

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
        return 7;
    }
}
