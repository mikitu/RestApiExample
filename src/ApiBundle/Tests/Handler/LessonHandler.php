<?php

namespace ApiBundle\Tests\Handler;
use ApiBundle\Handler\PageHandler;
use ApiBundle\Model\PageInterface;
use ApiBundle\Entity\Page;


class LessonHandler extends \PHPUnit_Framework_TestCase
{
    const LESSON_CLASS = 'ApiBundle\Tests\Handler\DummyLesson';

    /** @var lessonHandler */
    protected $lessonHandler;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }

        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::LESSON_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::LESSON_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::LESSON_CLASS));
    }

    public function testGet()
    {
        $id = 1;
        $lesson = $this->getLesson();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($lesson));
        $this->lessonHandler = $this->createlessonHandler($this->om, static::LESSON_CLASS, $this->formFactory);
        $this->lessonHandler->get($id);
    }

    public function testAll()
    {
        $offset = 1;
        $limit = 2;
        $lessons = $this->getLessons(2);
        $this->repository->expects($this->once())->method('findBy')
            ->with(array(), null, $limit, $offset)
            ->will($this->returnValue($lessons));
        $this->lessonHandler = $this->createlessonHandler($this->om, static::LESSON_CLASS, $this->formFactory);
        $all = $this->lessonHandler->all($limit, $offset);
        $this->assertEquals($lessons, $all);
    }

    public function testPost()
    {
        $title = 'title1';
        $body = 'body1';
        $parameters = array('title' => $title, 'body' => $body);
        $lesson = $this->getLesson();
        $lesson->setTitle($title);
        $lesson->setBody($body);
        $form = $this->getMock('ApiBundle\Tests\FormInterface');
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($lesson));
        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));
        $this->lessonHandler = $this->createlessonHandler($this->om, static::LESSON_CLASS, $this->formFactory);
        $lessonObject = $this->lessonHandler->post($parameters);
        $this->assertEquals($lessonObject, $lesson);
    }

    /**
     * @expectedException ApiBundle\Exception\InvalidFormException
     */
    public function testPostShouldRaiseException()
    {
        $title = 'title1';
        $body = 'body1';
        $parameters = array('title' => $title, 'body' => $body);
        $lesson = $this->getLesson();
        $lesson->setTitle($title);
        $lesson->setBody($body);
        $form = $this->getMock('ApiBundle\Tests\FormInterface');
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));
        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));
        $this->lessonHandler = $this->createlessonHandler($this->om, static::LESSON_CLASS, $this->formFactory);
        $this->lessonHandler->post($parameters);
    }

    public function testPut()
    {
        $title = 'title1';
        $body = 'body1';
        $parameters = array('title' => $title, 'body' => $body);
        $lesson = $this->getLesson();
        $lesson->setTitle($title);
        $lesson->setBody($body);
        $form = $this->getMock('ApiBundle\Tests\FormInterface');
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($lesson));
        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));
        $this->lessonHandler = $this->createlessonHandler($this->om, static::LESSON_CLASS, $this->formFactory);
        $lessonObject = $this->lessonHandler->put($lesson, $parameters);
        $this->assertEquals($lessonObject, $lesson);
    }

    public function testPatch()
    {
        $title = 'title1';
        $body = 'body1';
        $parameters = array('body' => $body);
        $lesson = $this->getLesson();
        $lesson->setTitle($title);
        $lesson->setBody($body);
        $form = $this->getMock('ApiBundle\Tests\FormInterface');
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($lesson));
        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));
        $this->lessonHandler = $this->createlessonHandler($this->om, static::LESSON_CLASS, $this->formFactory);
        $lessonObject = $this->lessonHandler->patch($lesson, $parameters);
        $this->assertEquals($lessonObject, $lesson);
    }

    protected function createlessonHandler($objectManager, $lessonClass, $formFactory)
    {
        return new lessonHandler($objectManager, $lessonClass, $formFactory);
    }

    protected function getLesson()
    {
        $lessonClass = static::LESSON_CLASS;
        return new $lessonClass();
    }

    protected function getLessons($maxLessons = 5)
    {
        $lessons = array();
        for ($i = 0; $i < $maxLessons; $i++) {
            $lessons[] = $this->getLesson();
        }
        return $lessons;
    }
}

class DummyLesson extends Lesson
{
}