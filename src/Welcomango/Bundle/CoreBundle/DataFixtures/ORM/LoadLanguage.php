<?php

namespace Welcomango\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Welcomango\Model\Language;

/**
 * Class LoadLanguageData
 */
class LoadLanguageData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $languages = array(
            ['French', 'FR', 'fr'],
            ['English', 'EN', 'england'],
            ['German', 'DE', 'de'],
            ['Italian', 'IT', 'it'],
            ['Spanish', 'ES', 'es'],
        );

        foreach ($languages as $lang) {
            $entry = new Language();
            $entry->setLanguage($lang[0]);
            $entry->setLanguageCode($lang[1], 0, 2);
            $entry->setFlagLabel($lang[2]);

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
