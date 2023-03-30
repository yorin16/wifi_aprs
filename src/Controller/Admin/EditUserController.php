<?php

namespace App\Controller\Admin;

use App\Form\EditUserType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EditUserController extends AbstractController
{

    public function __construct(private UserRepository $userRepository, private EntityManagerInterface $entityManager)
    {}

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function index($id, Request $request)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_admin', ['id' => $id]);
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}