<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations AS Rest;

class ApiController extends FOSRestController
{
    /**
     * @Rest\Get("/")
     */
    public function indexAction()
    {
        return "Hello Api";
    }
}
