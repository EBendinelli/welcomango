<?php

namespace Welcomango\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Welcomango\Model\Tag;

class LoadTagData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tags = array('Photography','Art','Cinema','Music','Politics','Culture','Wine','Food','Beer','Nature','Sport', 'Nightlife', 'History', 'Literature', 'Must See', 'Fashion', 'Unusual');

        foreach($tags as $tag){
            $entry = new Tag();
            $entry->setName($tag);
            $entry->setCreatedAt(new \DateTime());
            $entry->setUpdatedAt(new \DateTime());

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
