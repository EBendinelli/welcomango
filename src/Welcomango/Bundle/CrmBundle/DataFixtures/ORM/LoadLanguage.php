<?php

namespace Welcomango\Bundle\CrmBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Welcomango\Model\Language;

class LoadLanguageData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $languages = array('French', 'English', 'German', 'Italian', 'Spanish');

        foreach($languages as $lang){
            $entry = new Language();
            $entry->setLanguage($lang);
            $code = \strtoupper(\substr($lang, 0, 2));
            $entry->setLanguageCode($code);

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