<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SentryTestController extends AbstractController
{
    /**
     * @throws Exception
     */
    public function index()
    {
        //TODO: remove this test controller
        throw new Exception('test');
    }
}