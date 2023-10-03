<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SentryTestController extends AbstractController
{
    public function index()
    {
        //TODO: remove this test controller
        throw new NotFoundHttpException('test');
    }
}