<?php

namespace Welcomango\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Welcomango\Model\Experience;

class LoadExperienceData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $cityRepo = $manager->getRepository('Welcomango\Model\City');

        $userRepo = $manager->getRepository('Welcomango\Model\User');
        $users = $userRepo->findAll();

        $tagRepo = $manager->getRepository('Welcomango\Model\Tag');
        $tags = $tagRepo->findAll();

        $experiences = array(
            array('title' => 'A journey in Lyon', 'description' => 'Wandering around the city to taste beers and listen to good music', 'city' => $cityRepo->findOneBy(array('name' => 'Lyon'))),
            array('title' => 'Dreams of Sauna', 'description' => 'Wanna get hot? Follow me into the moistiest places and let\'s get naked', 'city' => $cityRepo->findOneBy(array('name' => 'Helsinki'))),
            array('title' => 'Italian nightlife', 'description' => 'Italians don\'t know how to party? Let me show you how wrong you are!', 'city' => $cityRepo->findOneBy(array('name' => 'Rome'))),
            array('title' => 'Football history', 'description' => 'Wanna discover what the country with best football club has to show? Follow me!', 'city' => $cityRepo->findOneBy(array('name' => 'Barcelone'))),
            array('title' => 'Prussian empire revival', 'description' => 'Back to the future for this small tour how a lost empire', 'city' => $cityRepo->findOneBy(array('name' => 'Vienna'))),
            array('title' => 'Green adventure', 'description' => 'Just stop behind considered as a tourist and get use to the local customs regarding weed', 'city' => $cityRepo->findOneBy(array('name' => 'Amsterdam'))),
            array('title' => 'Undeground Geneva', 'description' => 'Behind the UN, Beneath the banks, geneva has a lot to offer', 'city' => $cityRepo->findOneBy(array('name' => 'Geneva'))),
            array('title' => 'Skate to die', 'description' => 'If you do not understand how one can visit a city just by walking, it\'s time to join me', 'city' => $cityRepo->findOneBy(array('name' => 'London'))),
            array('title' => 'Geeky city', 'description' => 'You may have never think of this city as a place where something happen. Well, me neither', 'city' => $cityRepo->findOneBy(array('name' => 'Strasbourg'))),
            array('title' => 'Best waffles', 'description' => 'Food lover, just pick my number and let\'s find out where we can eat until we die of foodgasm', 'city' => $cityRepo->findOneBy(array('name' => 'Brussels')))
        );


        $i = 0;
        foreach($experiences as $experience){
            //Each experience has a creator
            $entry = new Experience();
            $entry->setCreator($users[array_rand($users)]);

            $entry->setTitle($experience['title']);
            $entry->setDescription($experience['description']);
            $entry->setCity($experience['city']);
            $entry->setCreatedAt(new \DateTime());
            $entry->setUpdatedAt(new \DateTime());
            $entry->setPublicationStatus('published');
            ($i%2 == 0 ? $entry->setFeatured(true) : $entry->setFeatured(false));
            $i++;

            $estimatedDuration = rand(1,8);
            $entry->setEstimatedDuration($estimatedDuration);

            $minimumDuration = 1;
            if($estimatedDuration == 1) $minimumDuration == $estimatedDuration;
            else if($estimatedDuration == 2) $minimumDuration == 2;
            else $minimumDuration = rand(1, $estimatedDuration);
            $entry->setMinimumDuration($minimumDuration);

            $entry->setMaximumDuration(rand($estimatedDuration,($estimatedDuration+4)));
            $entry->setPricePerHour(rand(5,50));
            $entry->setMaximumParticipants(rand(1,10));

            $randTags = array();
            for($i=0;$i<4;$i++){
                $randTag = rand(0,15);
                if(!isset($randTags[$randTag])){
                    $randTags[$randTag] = $tags[$randTag];
                    $entry->addTag($randTags[$randTag]);
                }
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