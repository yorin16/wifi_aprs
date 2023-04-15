<?php

namespace App\Controller\Admin\EditProject;

use App\Entity\Location;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EditProjectController extends AbstractController
{
    public function __construct(private ProjectRepository $projectRepository)
    {
    }

    public function index($project): Response
    {
        $project = $this->projectRepository->find($project);
        /* @var Collection|Location[] $locations */
        $locations = $project->getLocations();

        $questions = $this->projectRepository->findQuestionsByProjectId($project->getId());

        return $this->render('admin/editProject/index.html.twig', [
            'usersInProject' => $project->getUsers(),
            'locations' => $locations,
            'project' => $project,
            'questions' => $questions
        ]);
    }
}