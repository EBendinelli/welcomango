<?php

namespace Welcomango\Bundle\Admin\CrmBundle\DataFixtures\ORM;

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
        $userManager = $this->container->get('fos_user.user_manager');

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

            $user->setRoles(array('ROLE_USER'));

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
        return 1;
    }
}
