<?php

namespace ApiBundle\Tests\Fixtures\Entity;

use AppBundle\Entity\Lesson;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadLessonsData implements FixtureInterface
{
    static public $lessons = array();

    public function load(ObjectManager $manager)
    {
        $lesson = new Lesson();
        $lesson->setTitle('title');
        $lesson->setBody('body');
        $manager->persist($lesson);
        $manager->flush();
        self::$lessons[] = $lesson;
    }
}