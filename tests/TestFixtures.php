<?php

namespace App\Tests;

use App\Entity\Twouit;
use App\Entity\User;
use App\Enum\RoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture
{
    function __construct(private UserPasswordHasherInterface $passwordHasher) {}
    public function load(ObjectManager $manager): void
    {
        $roles[] = RoleEnum::USER;
        $nbUsers = rand(6, 15);
        $users = [];
        for ($i = 0; $i < $nbUsers; $i++) {
            $user = new User();
            $user->setName('Hell Diver ' . $i);
            $user->setMail('helldiver'. $i . '@democracy.com');
            $user->setLogin($user->getMail());
            $user->setPassword('password'. $i);
            $user->setDescription('I am a FREE CITIZEN ' . $i);

            // guarantee every user at least has ROLE_USER
            $user->setRoles($roles);
            if($i == 0)
                $user->setRoles([RoleEnum::ADMIN]);

            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $users[] = $user;
            $manager->persist($user);
        }

        for ($i = 0; $i < 3; $i++) {
            $users[$i]->addFriend($users[$i + 1]);
            $users[$i + 1]->addFriend($users[$i]);
            $manager->persist($users[$i]);
            $manager->persist($users[$i + 1]);
        }

        for ($i = 0; $i < 10; $i++) {
            $twouit = new Twouit();
            $twouit->setTitle('Twouit ' . $i);
            $twouit->setMsgContent('ALL FOR DEMOCRACY ' . $i);
            $twouit->setEntryDate(new \DateTime());
            $twouit->setUser($users[rand(0, $nbUsers - 1)]);
            $manager->persist($twouit);
        }
        $manager->flush();
    }
}
