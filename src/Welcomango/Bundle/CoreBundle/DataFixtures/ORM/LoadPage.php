<?php

namespace Welcomango\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Welcomango\Model\Page;

class LoadPageData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $categoryRepo = $manager->getRepository('Welcomango\Model\Category');
        $userRepo = $manager->getRepository('Welcomango\Model\User');

        $pages = \simplexml_load_file(realpath(__DIR__.'/../../../../Data/content.xml')) or die("Error: Cannot create object");

        foreach($pages as $page){
            $entry = new Page();
            $entry->setTitle($page->title);
            $entry->setContent($page->content);
            $entry->setAuthor($userRepo->findOneBy(['username' => 'admin']));
            $entry->setCategory($categoryRepo->findOneBy(['name' => $page->category]));
            $entry->setCreatedAt(new \DateTime());
            $entry->setUpdatedAt(new \DateTime());
            $entry->setPublicationStatus('published');

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
        return 13;
    }
}
