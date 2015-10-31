<?php

namespace Welcomango\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Welcomango\Model\User;

class LoadUserData extends AbstractFixture  implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function load(ObjectManager $manager)
    {
        // Get our userManager, you must implement `ContainerAwareInterface`
        $cities = array('Brussels', 'Lyon', 'Geneva', 'London', 'Rome', 'Amsterdam', 'Milan', 'Helsinki', 'Vienna', 'Barcelone');
        $genders =  array("M","F","O");
        $occupations = array("Student", "Teacher", "Photograph", "Traveler", "Taxi Driver", "Hacker", "HR", "Cleaning Lady", "Doctor", "Researcher","Communication Director", "Marketing addict", "Fashion Designer");


        $userManager = $this->container->get('fos_user.user_manager');
        $cityRepo = $manager->getRepository('Welcomango\Model\City');

        // Create our user and set details
        $admin = $userManager->createUser();
        $admin->setUsername('admin');
        $admin->setEmail('admin@welcomango.com');
        $admin->setPlainPassword('admin');
        $admin->setLastName('Palmer');
        $admin->setFirstName('Jack');
        $admin->setPhone('0680154251');
        $admin->setCreatedAt(new \DateTime());
        $admin->setUpdatedAt(new \DateTime());
        $birthdate = date_create(date("d-m-Y",636263535));
        $admin->setBirthdate($birthdate);
        //$user->setPassword('3NCRYPT3D-V3R51ON');
        $admin->setEnabled(true);
        $admin->setRoles(array('ROLE_SUPER_ADMIN', 'ROLE_ADMIN'));
        $admin->setDescription('I\'m the admin, don\'t fuck with me.');
        $fromCity = $cityRepo->findOneBy(array('name' => $cities[rand(0,9)] ));
        $currentCity = $cityRepo->findOneBy(array('name' => $cities[rand(0,9)] ));
        $admin->setFromCity($fromCity);
        $admin->setCurrentCity($currentCity);
        $admin->setGender('M');
        $admin->setOccupation('Business Manager');

        // Update the user
        $userManager->updateUser($admin, true);

        $listNames = array('Alexandre', 'Marine', 'Anna', 'Jackie', 'Michel', 'Jaybe', 'Eliot');

        $i=0;
        foreach ($listNames as $name) {
            $user = $userManager->createUser();

            $user->setUsername($name);
            $user->setPassword($name);
            $user->setEmail($name.'@mail.com');
            $user->setPlainPassword('admin');
            $user->setLastName($name);
            $user->setFirstName($name);
            $user->setPhone('0000000000');
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());
            $birthdate = date_create(date("d-m-Y",rand(293832465,915076335)));
            $user->setBirthdate($birthdate );
            ($i%2 == 0 ? $user->setEnabled(true) : $user->setEnabled(false));
            $i++;
            $user->setEnabled(true);
            $fromCity = $cityRepo->findOneBy(array('name' => $cities[rand(0,9)] ));
            $currentCity = $cityRepo->findOneBy(array('name' => $cities[rand(0,9)] ));
            $user->setFromCity($fromCity);
            $user->setCurrentCity($currentCity);
            $user->setGender($genders[rand(0,2)]);
            $user->setOccupation($occupations[rand(0,12)]);

            $user->setRoles(array('ROLE_USER'));

            $user->setDescription('Hi there, I\'m '.$name.' and I\'ve been living in there for some time already!

                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus at imperdiet erat. Vivamus ut aliquam magna. Aenean vel mattis lorem, vel fringilla elit. Ut eu congue ligula, vel porta tellus. Maecenas tempor varius mauris, vitae imperdiet metus egestas in. Fusce ut suscipit ante. Mauris mattis purus sem, a gravida metus placerat id. Pellentesque quis nibh efficitur, venenatis orci semper, lobortis orci. Aliquam id condimentum justo. In feugiat enim nunc, et viverra nibh porttitor vel. Suspendisse finibus magna sed sapien commodo, eget dictum tellus pretium. Duis consequat bibendum semper. Curabitur fermentum mollis neque, nec ullamcorper magna interdum nec. Quisque eget finibus lacus, ut auctor libero. Donec efficitur ultrices nisi, in rutrum sapien feugiat sit amet. Suspendisse ullamcorper dignissim nulla, a blandit magna rhoncus vel.
                ');

            $manager->persist($user);
            $userManager->updateUser($user, true);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        //Define the order in which the fixtures are executed
        return 3;
    }
}
