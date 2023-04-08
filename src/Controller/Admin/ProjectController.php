<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\Project\ProjectCreateType;
use App\Form\Project\ProjectEditType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    public function edit($id, Request $request, Project $project): RedirectResponse|Response
    {
        $form = $this->createForm(ProjectEditType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($project);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_project', ['id' => $id]);
        }

        return $this->render('admin/project/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function delete(Project $project, Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete_user_' . $project->getId(), $request->request->get('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
            $this->addFlash('success', 'User deleted');
        } else {
            $this->addFlash('error', 'Invalid CSRF token');
        }

        return $this->redirectToRoute('admin_project');
    }

    public function confirmDelete(Project $project): Response
    {
        return $this->render('admin/project/confirm_delete.html.twig', [
            'project' => $project,
        ]);
    }
}