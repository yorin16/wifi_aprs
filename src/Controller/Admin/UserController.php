<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{

    public function __construct(private UserRepository $userRepository, private EntityManagerInterface $entityManager)
    {
    }

    public function index(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }

    public function edit($id, Request $request, User $user): RedirectResponse|Response
    {

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_admin', ['id' => $id]);
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}