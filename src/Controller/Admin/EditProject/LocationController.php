<?php

namespace App\Controller\Admin\EditProject;

use App\Entity\Location;
use App\Entity\Project;
use App\Form\Location\LocationCreateType;
use App\Form\Location\LocationEditType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LocationController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private ProjectRepository $projectRepository)
    {
    }

    public function index($project): Response
    {
        $project = $this->projectRepository->find($project);
        $locations = $project->getLocations();

        return $this->render('admin/editProject/location/index.html.twig', [
            'project' => $project,
            'locations' => $locations
        ]);
    }

    public function add($project, Request $request): Response
    {
        $project = $this->projectRepository->find($project);

        $location = new Location();
        $location->setProject($project);
        $form = $this->createForm(LocationCreateType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($location);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_edit_project', ['project' => $project->getId() ]);
        }

        return $this->render('admin/editProject/location/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Request $request, Project $project, Location $location): Response
    {
        $form = $this->createForm(LocationEditType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($location);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_edit_project', ['project' => $project->getId()]);
        }

        return $this->render('admin/editProject/location/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function delete(Location $location, Project $project, Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete_location_' . $location->getId(), $request->request->get('_token'))) {
            $entityManager->remove($location);
            $entityManager->flush();
            $this->addFlash('success', 'Location deleted');
        } else {
            $this->addFlash('error', 'Invalid CSRF token');
        }

        return $this->redirectToRoute('admin_edit_project', ['project' => $project->getId()]);
    }

    public function confirmDelete(Location $location, Project $project): Response
    {
        return $this->render('admin/editProject/location/confirm_delete.html.twig', [
            'location' => $location,
            'project' => $project
        ]);
    }
}