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
            ['title' => 'The Team', 'content' => '<h1>About Us</h1>
<h1><hr>
</h1>
<blockquote>
<p><span style="line-height: normal;">Young, cheerful and determined, we want Welcomango to become real because we would be the first to use it. Travelers, explorers and discoverers, we are the brain and the hands of this project. Join us in the venture.&nbsp;</span></p>
<p><span style="line-height: normal;"><br></span></p>
<p><span style="line-height: normal;">Feel free to contact us or give us feedback about your experience</span></p>
</blockquote>', 'category' => $categoryRepo->findOneBy(array('name' => 'About'))],
            ['title' => 'Legal', 'content' => 'All rights reserved', 'category' => $categoryRepo->findOneBy(array('name' => 'About'))],
            ['title' => 'Welcomango is launched!', 'content' => 'Looks like we\'re in!', 'category' => $categoryRepo->findOneBy(array('name' => 'News'))],
        );

        foreach($pages as $page){
            $entry = new Page();
            $entry->setTitle($page['title']);
            $entry->setContent($page['content']);
            $entry->setAuthor($userRepo->findOneBy(['username' => 'admin']));
            $entry->setCategory($page['category']);
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
