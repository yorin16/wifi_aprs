<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BackupRegisterController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager,private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function index(Request $request)
    {
        $submittedPassword = $request->get('password');

        if($submittedPassword === 'secretkdg'){
            $user = new User();
            $user->setUsername('test');
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    'test1234'
                )
            );
            $user->setRoles(['ROLE_ADMIN','ROLE_USER']);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new Response('Basic account created for user "yorin" with roles ROLE_ADMIN and ROLE_USER');
        } else {
            return new Response('Wrong password');
        }
    }

}