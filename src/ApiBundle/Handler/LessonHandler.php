<?php
/**
 * Created by PhpStorm.
 * User: mbucse
 * Date: 13/03/2016
 * Time: 20:05
 */

namespace ApiBundle\Handler;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use ApiBundle\Model\LessonInterface;
use ApiBundle\Exception\InvalidFormException;
use ApiBundle\Form\LessonType;

class LessonHandler implements LessonHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }
    /**
     * Get a Lesson.
     *
     * @param mixed $id
     *
     * @return LessonInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }
    /**
     * Get a list of Lessons.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }
    /**
     * Create a new Lesson.
     *
     * @param array $parameters
     *
     * @return LessonInterface
     */
    public function post(array $parameters)
    {
        $lesson = $this->createLesson();
        return $this->processForm($lesson, $parameters, 'POST');
    }
    /**
     * Edit a Lesson.
     *
     * @param LessonInterface $lesson
     * @param array         $parameters
     *
     * @return LessonInterface
     */
    public function put(LessonInterface $lesson, array $parameters)
    {
        return $this->processForm($lesson, $parameters, 'PUT');
    }
    /**
     * Partially update a Lesson.
     *
     * @param LessonInterface $lesson
     * @param array         $parameters
     *
     * @return LessonInterface
     */
    public function patch(LessonInterface $lesson, array $parameters)
    {
        return $this->processForm($lesson, $parameters, 'PATCH');
    }
    /**
     * Processes the form.
     *
     * @param LessonInterface $lesson
     * @param array         $parameters
     * @param String        $method
     *
     * @return LessonInterface
     *
     * @throws \ApiBundle\Exception\InvalidFormException
     */
    private function processForm(LessonInterface $lesson, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new LessonType(), $lesson, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $lesson = $form->getData();
            $this->om->persist($lesson);
            $this->om->flush($lesson);
            return $lesson;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }
    private function createLesson()
    {
        return new $this->entityClass();
    }
}