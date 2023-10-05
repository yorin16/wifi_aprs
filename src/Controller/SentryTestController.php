<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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