<?php

namespace Welcomango\Bundle\ExperienceBundle\DataFixtures\ORM;

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

        $experiences = array(
            array('title' => 'A journey in Lyon', 'description' => 'Wandering around the city to taste beers and listen to good music', 'city' => $cityRepo->findOneBy(array('name' => 'Lyon'))),
            array('title' => 'Dreams of Sauna', 'description' => 'Wanna get hot? Follow me into the moistiest places and let\'s get naked', 'city' => $cityRepo->findOneBy(array('name' => 'Helsinki'))),
            array('title' => 'Italian nightlife', 'description' => 'Italians don\'t know how to party? Let me show you how wrong you are!', 'city' => $cityRepo->findOneBy(array('name' => 'Rome'))),
            array('title' => 'Football history', 'description' => 'Wanna discover what the country with best football club has to show? Follow me!', 'city' => $cityRepo->findOneBy(array('name' => 'Barcelona'))),
            array('title' => 'Prussian empire revival', 'description' => 'Back to the future for this small tour how a lost empire', 'city' => $cityRepo->findOneBy(array('name' => 'Vienna'))),
            array('title' => 'Green adventure', 'description' => 'Just stop behind considered as a tourist and get use to the local customs regarding weed', 'city' => $cityRepo->findOneBy(array('name' => 'Amsterdam'))),
            array('title' => 'Undeground Geneva', 'description' => 'Behind the UN, Beneath the banks, geneva has a lot to offer', 'city' => $cityRepo->findOneBy(array('name' => 'Geneva')))
        );


        foreach($experiences as $experience){
            $entry = new Experience();
            $entry->setTitle($experience['title']);
            $entry->setDescription($experience['description']);
            $entry->setCity($experience['city']);
            $entry->setCreatedAt(new \DateTime());
            $entry->setUpdatedAt(new \DateTime());

            $estimatedDuration = rand(1,8);
            $entry->setEstimatedDuration($estimatedDuration);

            $minimumDuration = 0;
            if($estimatedDuration == 1) $minimumDuration == $estimatedDuration;
            else if($estimatedDuration == 2) $minimumDuration == 2;
            else $minimumDuration = rand(1, $estimatedDuration);
            $entry->setMinimumDuration($minimumDuration);

            $entry->setMaximumDuration(rand($estimatedDuration,($estimatedDuration+4)));
            $entry->setPricePerHour(rand(5,50));
            $entry->setMaximumParticipants(rand(1,10));

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
        return 6;
    }
}