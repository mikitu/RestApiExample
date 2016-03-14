<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Lesson;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

class LoadLessonData implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker  = Faker::create();

        for ($i = 0; $i < 1000; $i++) {
            $lesson = new Lesson();
            $lesson->setTitle($faker->sentence(5));
            $lesson->setBody($faker->paragraph(10));
            $manager->persist($lesson);
        }
        $manager->flush();
    }
    public function getOrder()
    {
        return 1;
    }

}