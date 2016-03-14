<?php
/**
 * Created by PhpStorm.
 * User: mbucse
 * Date: 13/03/2016
 * Time: 20:10
 */

namespace ApiBundle\Model;


interface LessonInterface
{
    /**
     * Set title
     *
     * @param string $title
     * @return LessonInterface
     */
    public function setTitle($title);
    /**
     * Get title
     *
     * @return string
     */
    public function getTitle();
    /**
     * Set body
     *
     * @param string $body
     * @return LessonInterface
     */
    public function setBody($body);
    /**
     * Get body
     *
     * @return string
     */
    public function getBody();
}