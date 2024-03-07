<?php

namespace App\DataFixtures;

use App\Entity\Twouit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $nbUsers = rand(6, 15);
        $users = [];
        for ($i = 0; $i < $nbUsers; $i++) {
            $user = new User();
            $user->setName('Hell Diver ' . $i);
            $user->setMail('helldiver'. $i . '@democracy.com ');
            $user->setLogin($user->getMail());
            $user->setPassword('password'. $i);
            $user->setDescription('I am a FREE CITIZEN ' . $i);
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
