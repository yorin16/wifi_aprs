<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\Project\ProjectCreateType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager, private ProjectRepository $projectRepository)
    {
    }

    public function index(): Response
    {
        $fields = $this->projectRepository->getFields();
        $projects = $this->projectRepository->findAll();

        return $this->render('admin/project/index.html.twig', [
            'fields' => $fields,
            'projects' => $projects
        ]);
    }

    public function add(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectCreateType::class, $project);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($project);
            $this->entityManager->flush();

             return $this->redirectToRoute('admin_project');
        }

        return $this->render('project/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}