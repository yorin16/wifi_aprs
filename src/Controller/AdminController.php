<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    public function page(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users
        ]);
    }
}