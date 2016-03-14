<?php
/**
 * Created by PhpStorm.
 * User: mbucse
 * Date: 13/03/2016
 * Time: 20:08
 */

namespace ApiBundle\Handler;

use ApiBundle\Model\LessonInterface;

interface LessonHandlerInterface
{
    /**
     * Get a Lesson given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return LessonInterface
     */
    public function get($id);
    /**
     * Get a list of Lessons.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);
    /**
     * Post Lesson, creates a new Lesson.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return LessonInterface
     */
    public function post(array $parameters);
    /**
     * Edit a Lesson.
     *
     * @api
     *
     * @param LessonInterface   $Lesson
     * @param array           $parameters
     *
     * @return LessonInterface
     */
    public function put(LessonInterface $Lesson, array $parameters);
    /**
     * Partially update a Lesson.
     *
     * @api
     *
     * @param LessonInterface   $Lesson
     * @param array           $parameters
     *
     * @return LessonInterface
     */
    public function patch(LessonInterface $Lesson, array $parameters);
}