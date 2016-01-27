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
            ['title' => 'The Team',
                'content' => '<h1>The Team</h1>
<hr />
<blockquote>
<p><span style="line-height: normal;">Young, cheerful and determined, we want Welcomango to become real because we would be the first to use it. Travelers, explorers and discoverers, we are the brain and the hands of this project. Join us in the venture.&nbsp;</span></p>
</blockquote>
<p><span style="line-height: normal;">&nbsp;<img class="wow fadeInUp animated" style="border-radius: 50%; display: block; margin-left: auto; margin-right: auto;" src="../../../../bundles/welcomangocore/images/team/Eliot.jpg" alt="" width="200" height="200" /></span></p>
<h3 style="text-align: center;"><span style="line-height: normal;">Eliot</span></h3>
<p>Born in Lyon, France, Eliot has a double background in Political Science and IT and&nbsp;is willing to put it at use! Fond of music and a musican himselft,&nbsp;he&nbsp;lived&nbsp;in Taiwan, Brussels, Geneva, and the&nbsp;US, worked for web agencies and International Organizations for some time before deciding to forge&nbsp;his own way.&nbsp;Leading the Welcomango project,&nbsp;he manage&nbsp;a wide range of aspects of the project from development to management and social&nbsp;outreach.&nbsp;</p>
<p>&nbsp;</p>
<p><img class="wow fadeInLeft animated" style="border-radius: 50%; display: block; margin-left: auto; margin-right: auto;" src="../../../../bundles/welcomangocore/images/team/Jean-Baptiste.jpg" alt="" width="200" height="200" /></p>
<h3 style="text-align: center;">Jean-Baptiste</h3>
<p style="text-align: left;">Born in Lyon,&nbsp;Jean-Baptiste made his way in the web development field with his own teeth! Not only typing on his keyboard he&nbsp;loves hitting things that make sound (Especially brazilians ones, ever heard of Batucada?). JB is the lead&nbsp;developper and&nbsp;is making magic with his computer to support this cute little website. Any tech question? He\'s the one!</p>
<p style="text-align: left;"><img class="wow fadeInDown animated" style="border-radius: 50%; display: block; margin-left: auto; margin-right: auto;" src="../../../../bundles/welcomangocore/images/team/Matthieu.jpg" alt="" width="200" height="200" /></p>
<h3 style="text-align: center;">Matthieu&nbsp;</h3>
<p>Born in Besan&ccedil;on (France) in 1987, Matthieu holds a BA degree in Communication studies and a MA degree in Human Rights. He began to travel during his studies doing a communication internship in Canada before going to the Netherlands for a year with the Erasmus exchange programme. In 2010, Matthieu volunteered in Burkina Faso for a year where he decided to give a new perspective to his career. He was selected to be part of the Erasmus Mundus Human Rights Practice MA programme run as a joint partnership between Gothenburg University (Sweden), Roehampton University (UK) and Troms&oslash; University (Norway). This programme gave him the opportunity to have some professional experience in the UK, Norway and Denmark. He is in charge of the communication aspect of the project, definitely the coolest guy you\'ll have the chance to meet. If you ignore the fact he supports a forgotten french foot team called&nbsp;FCSM.</p>',
                'category' => $categoryRepo->findOneBy(array('name' => 'About'))],

            ['title' => 'Legal', 'content' => 'All rights reserved', 'category' => $categoryRepo->findOneBy(array('name' => 'About'))],

            ['title' => 'Thanks',
                'content' => '<h1>Thanks</h1>
<hr />
<p>A list of the different free&nbsp;tools we used that helped us to build this awesome website</p><br/>
<p><a href="https://symfony.com/">The Symfony project</a>: The main framework of the project. We could not say here how much we love Symfony so we\'re going to let you check by yourself</p>
<p><a href="http://mynameismatthieu.com/WOW/">Wow.js</a>: Funny little tool which provides good looking animation. Plus the website contains Doge, how awesome</p>
<p><a href="http://fontawesome.io">Fontawesome</a>: Which help us have these wonderful little icons everywhere</p>
<p><a href="http://dimsemenov.com/plugins/magnific-popup/">Magnific Popup</a>:&nbsp;For the great image slideshow on the experience page</p>
<p>&nbsp;</p>
<p>We would also like to give a special thanks to Riccardo David Marriano and Daniel Hernandez without whom this idea would not exist. Another special thanks to Karoliina&nbsp;Lohiniva for the help and support. And of course to anyone would support and help us&nbsp;to make Welcomango alive.</p>',
                'category' => $categoryRepo->findOneBy(array('name' => 'About'))],

            ['title' => 'Welcomango is launched!', 'content' => 'Looks like we\'re in!', 'category' => $categoryRepo->findOneBy(array('name' => 'News'))],
            ['title' => 'How To', 'content' => '<p style="letter-spacing: 0.14px;">Welcomango is aiming at propose a simple and intuitive experience so that anyone has a chance to find the perfect welcomanguide for their next trip. And become one of them.</p><p style="letter-spacing: 0.14px;">The concept is simple and rely in 5 steps:</p><p style="letter-spacing: 0.14px;"><br></p><h2 class="wow slideInLeft" style="letter-spacing: 0.14px;">Test good?</h2><p style="letter-spacing: 0.14px;"><br></p> ',
                'category' => $categoryRepo->findOneBy(array('name' => 'How To'))],
            ['title' => 'Proposing and Experience', 'content' => '<h3>Me guide? But I ain\'t got nothing to show!</h3>
<h5>Proposing an experience of your city, a not&nbsp;as complicate task as you imagine it</h5>
<hr />
<p>Most of the time when we suggested the idea of Welcomango to travelers, adventurers or even random friends who like to travel from time to time, they loved the idea. But when it came to proposing a tour of their city, a lot of them had the same answer:</p>
<blockquote>
<p><em>Me? But I don\'t know&nbsp;anything&nbsp;about this city!&nbsp;What could I show, people would get bored!</em></p>
</blockquote>
<p>Well, that the whole point of Welcomango. It\'s not because you don\'t know when your city was build, who&nbsp;was the last mayor and when the main&nbsp;square was built that you don\'t have anything to give to travelers. Actually, <strong>that\'s even better that way!</strong></p>
<p>&nbsp;</p>
<p>You may ignore it, but you actually know a lot about your city. And when I say a lot, it's A LOT! You've probably been around for some time and I am sure you&nbsp;experienced the city in many ways. Let me show you what you probably:</p>
<ul>
<li>You got lost in your city. And discovered some weird spots that are still in your mind</li>
<li>You had a marvelous dinner with friends at this super cool place which you loved</li>
<li>You played with your basketball team in&nbsp;a full of screet-art area which blown your mind</li>
<li>You&nbsp;shopped in this really cute shop,&nbsp;lost in the suburb, which sells such awesome items</li>
<li>You had a drink (or more?) with people you like and enjoyed the music in the back in this underground place you would have never suspected to exist.</li>
<li>Talking about music, you probably went to see concert in a pub and discovered a magical band</li>
</ul>
<p>And of course, <strong>you lived the city</strong>. You got used to its public transport delay. You worked or studied in a specific place with a great&nbsp;sandwich-seller which fullfilled your stomach so many times&nbsp;without kiling your budget. If you\'ve been living here for a long time maybe you also know this place where your grand father used to paint. <strong>And the beauty of this is that everywhere you go, you write little stories</strong>, you experience the city, its habits, its culture, and that gives you plenty of anecdotes to tell.</p>
<p>See the point? You don\'t have to be an expert in something, an architecture lover or a perfect tour guide to experience and know your own city. <strong>You already have something to show</strong> since you live in&nbsp;this&nbsp;city, <strong>and that\'s exactly what we want&nbsp;you&nbsp;to share </strong>:).</p>
<br/>
<p><em>Now, think about it the other way around.</em></p>
<p>Read what I\'ve listed above and imagine yourself discovering a new place through these spots. Wouldn\'t it be awesome? Sit with someone living in the city in a bar he randomly discovered? Go to a concert in a place that doesn\'t even have a Facebook page? See where this person\'s grand father used to fight for revolution? I\'m sure you see the point now.</p>
<p>I\'m sure you now understand why we wanted Welcomango to exist and what it really. <strong>In a few words: It\'s about experiencing places through people and through their personal habits</strong></p>',
         'category' => $categoryRepo->findOneBy(array('name' => 'How To'))],
        );

        foreach($pages as $page){
            $entry = new Page();
            $entry->setTitle($page['title']);
            $entry->setContent($page['content']);
            $entry->setAuthor($userRepo->findOneBy(['username' => 'admin']));
            $entry->setCategory($page['category']);
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
        return 12;
    }
}
