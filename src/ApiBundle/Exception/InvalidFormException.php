<?php
/**
 * Created by PhpStorm.
 * User: mbucse
 * Date: 13/03/2016
 * Time: 20:28
 */

namespace ApiBundle\Exception;


class InvalidFormException extends \RuntimeException
{
    protected $form;

    public function __construct($message, $form = null)
    {
        parent::__construct($message);
        $this->form = $form;
    }

    /**
     * @return array|null
     */
    public function getForm()
    {
        return $this->form;
    }
}