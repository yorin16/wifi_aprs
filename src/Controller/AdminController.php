<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    public function page(): Response
    {
        return $this->render('admin/index.html.twig', [
        ]);
    }
}