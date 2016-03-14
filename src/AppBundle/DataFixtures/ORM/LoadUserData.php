<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

class LoadUserData implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker  = Faker::create();

        for ($i = 0; $i < 150; $i++) {
            $user = new User;
            $user->setUsername($faker->unique()->userName);
            $user->setPlainPassword($faker->unique()->password(8));
            $user->setEmail($faker->unique()->email);
            $user->setEnabled(true);
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

}