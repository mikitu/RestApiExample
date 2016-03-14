<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Comment;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCommentData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $faker  = Faker::create();
        $em = $this->container->get('doctrine')->getEntityManager('default');
        $lessonRepository = $em->getRepository('AppBundle:Lesson');
        $userRepository = $em->getRepository('AppBundle:User');
        for ($i = 0; $i < 200; $i++) {
            $comment = new Comment();
            $comment->setComment($faker->sentence(5));
            $comment->setLesson($lessonRepository->find(rand(1, 1000)));
            $comment->setUser($userRepository->find(rand(1, 150)));
            $comment->setRating(rand(1,5));
            $manager->persist($comment);
        }
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}