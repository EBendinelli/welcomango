<?php

namespace Welcomango\Bundle\CrmBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Welcomango\Model\Language;

class LoadLanguageData implements FixtureInterface
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
}