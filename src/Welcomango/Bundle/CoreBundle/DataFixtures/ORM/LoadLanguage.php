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
            ['language.french', 'FR', 'fr'],
            ['language.english', 'EN', 'england'],
            ['language.german', 'DE', 'de'],
            ['language.italian', 'IT', 'it'],
            ['language.spanish', 'ES', 'es'],
            ['language.croatian', 'HR', 'hr'],
            ['language.bulgarian', 'BG', 'bg'],
            ['language.czech', 'CS', 'cs'],
            ['language.danish', 'DA', 'da'],
            ['language.dutch', 'NL', 'nl'],
            ['language.estonian', 'ET', 'et'],
            ['language.finnish', 'FI', 'fi'],
            ['language.greek', 'EL', 'el'],
            ['language.hungarian', 'HU', 'hu'],
            ['language.irish', 'GA', 'ga'],
            ['language.latvian', 'LV', 'la'],
            ['language.lithuanian', 'LT', 'lt'],
            ['language.maltese', 'MT', 'mt'],
            ['language.spanish', 'PL', 'pl'],
            ['language.portuguese', 'PT', 'pt'],
            ['language.romanian', 'RO', 'ro'],
            ['language.slovak', 'SK', 'sk'],
            ['language.slovene', 'SL', 'sl'],
            ['language.swedish', 'SV', 'sv'],
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
