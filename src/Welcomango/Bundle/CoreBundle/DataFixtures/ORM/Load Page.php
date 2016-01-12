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

        $pages = array(
            ['title' => 'About us', 'content' => 'We are dreamers, a young cheerful team who wants to believe that traveling should not be reduced to taking pictures of the Eiffel tower', 'category' => $categoryRepo->findOneBy(array('name' => 'Core'))],
            ['title' => 'Legal', 'content' => 'All rights reserved', 'category' => $categoryRepo->findOneBy(array('name' => 'Core'))],
            ['title' => 'Welcomango is launched!', 'content' => 'Looks like we\'re in!', 'category' => $categoryRepo->findOneBy(array('name' => 'News'))],
        );

        foreach($pages as $page){
            $entry = new Page();
            $entry->setTitle($page['title']);
            $entry->setContent($page['content']);
            $entry->setAuthor($userRepo->findOneBy(['username' => 'admin']));
            $entry->addCategory($page['category']);
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
        return 12;
    }
}
