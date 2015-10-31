<?php

namespace Welcomango\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Welcomango\Model\SpokenLanguage;

class LoadSpokenLanguageData extends AbstractFixture  implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userRepo = $manager->getRepository('Welcomango\Model\User');
        $users = $userRepo->findAll();

        $languageRepo = $manager->getRepository('Welcomango\Model\Language');
        $languages = $languageRepo->findAll();

        for($i=0;$i<20;$i++){
            $randomLevel = rand(1,3);
            $oneLanguage = $languages[array_rand($languages)];
            $oneUser = $users[array_rand($users)];

            if(!$oneUser->hasSpokenLanguage($oneLanguage)) {
                $entry = new SpokenLanguage();
                $entry->setUser($oneUser);
                $entry->setLanguage($oneLanguage);
                $entry->setLevel($randomLevel);
            }

            $manager->persist($entry);
            $manager->flush();
        }

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
