<?php

namespace Welcomango\CrmBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Welcomango\Model\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface{

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
        $admin->setLastName('Jack');
        $admin->setFirstName('Palmer');
        $admin->setPhone('0680154251');
        $admin->setCreatedAt(new \DateTime());
        $admin->setUpdatedAt(new \DateTime());
        //$user->setPassword('3NCRYPT3D-V3R51ON');
        $admin->setEnabled(true);
        $admin->setRoles(array('ROLE_ADMIN'));

        // Update the user
        $userManager->updateUser($admin, true);

        $listNames = array('Alexandre', 'Marine', 'Anna');

        foreach ($listNames as $name) {
            // On crée l'utilisateur
            $user = $userManager->createUser();

            // Le nom d'utilisateur et le mot de passe sont identiques
            $user->setUsername($name);
            $user->setPassword($name);
            $user->setEmail($name.'@mail.com');
            $user->setPlainPassword('admin');
            $user->setLastName($name);
            $user->setFirstName($name);
            $user->setPhone('0000000000');
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());

            // On définit uniquement le role ROLE_USER qui est le role de base
            $user->setRoles(array('ROLE_USER'));
            // On le persiste
            $manager->persist($user);
            $userManager->updateUser($user, true);
        }
    }
}