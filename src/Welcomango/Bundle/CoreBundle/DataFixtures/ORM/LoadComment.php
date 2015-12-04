<?php

namespace Welcomango\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Welcomango\Model\Comment;

class LoadCommentData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $bookingRepo = $manager->getRepository('Welcomango\Model\Booking');
        $bookings = $bookingRepo->findAll();

        $comments = array(
            'Awesome visit given by an awesome dude, I really appreciated the time you gave us',
            'I definitely recommend this experience. It is a unique chance to discover a hidden part of this city in company of a very interesting person' ,
            'Middly interesting experience. I must say some moment were particulary exciting but the global thing was a bit too long with a lot of walking' ,
            'One thing to say: I hope I can meet you again in a different city' ,
            'Such an awesome night with Marine. She really know where to party and where things happen in the city' ,
            'I would have never imagined this town to be so surprising. Thanks to Jaybe I had one the best time stumbling upon unexpected abandonned places. Awesome.',
            'tis quis sollicitudin ut, blandit vitae tortor. Curabitur blandit fringilla orci non mattis. Donec elementum suscipit sem ut rhoncus. Praesent justo diam, sodales ' ,
            'get bibendum sapien diam vitae enim. Fusce non nisl eu ex tempor fringilla a ut lectus. Fusce luctus elementum nisl at fringilla. Nam a dui dapibus, gravida augue in, finibus erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc cursus, risus vitae iaculis suscipit, tellus purus accumsan dolor, ac ultricies sapien mi tempus magna. Morbi vel dignissim arcu, sit amet accumsan quam. Suspendisse bibendum porttitor lacus, eget consequat purus. Nullam tincidunt in eros id pulvinar. Quisq' ,
            ' volutpat velit varius libero porta vestibulum. Aliquam aliquet molestie magna non fermentum. Curabitur porttitor quam ut convallis viverra. In eros purus, commodo id pellentesque id, iaculis id mauris. Sed dignissim vestibulum lorem, vel volutpat elit lobortis in. Donec aliquam magna vitae turpis feugiat placerat. Suspendisse nunc erat, elementum non malesuada eu, interdum pulvinar magna. Quisque et dolor varius tortor egestas mollis in mollis mauris. Curabitur pretium magna vitae quam malesuada, at commodo turpis volutpat. Nullam fermentum ex convallis justo posuere aliquet. Suspendisse sed sapien eget nisl vulputate faucibus ac id orci. Sed id vulputate velit, vitae condimentum nibh. Pellentesque consectetur lectus nec lacus volutpat hendrerit.',
        );

        //Associate random comment to a random happened meeting
        $i = 0;
        foreach($bookings as $booking){
            if($booking->getStatus() == 'Happened'){
                $comment = new Comment();
                $comment->setPoster($booking->getUser());
                $experience = $booking->getExperience();
                $comment->setReceiver($experience->getCreator());
                $comment->setBooking($booking);

                $comment->setCreatedAt(new \Datetime());
                $comment->setUpdatedAt(new \Datetime());
                $comment->setValidated(true);
                $comment->setDeleted(false);

                //One comment on 5 is featured
                $rand = rand(0,4);
                if($rand == 4){
                    $comment->setFeatured(true);
                }else{
                    $comment->setFeatured(false);
                }

                $comment->setBody($comments[$i]);
                $i++;
                if($i == count($comments)){
                    $i = 0;
                }

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        //Define the order in which the fixtures are executed
        return 10;
    }
}
