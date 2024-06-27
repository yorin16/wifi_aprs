<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    public function index(Security $security): Response
    {
        /** @var User $user */
        $user = $security->getUser();

        if($user === null) {
            $project = null;
        } else {
            $project = $user->getProject()->getId();
        }

        return $this->render('index.html.twig', [
            'projectId' =>  $project
        ]);
    }
}