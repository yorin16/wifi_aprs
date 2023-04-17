<?php

namespace App\Controller\Admin\EditProject;

use App\Entity\Location;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EditProjectController extends AbstractController
{
    public function __construct(private ProjectRepository $projectRepository)
    {
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function index($project): Response
    {
        $project = $this->projectRepository->find($project);
        /* @var Collection|Location[] $locations */
        $locations = $project->getLocations();

        $questions = $project->getQuestions();
        $questionsWithoutLocation = $this->projectRepository->CountQuestionsInProjectWithoutLocation($project->getId());

        return $this->render('admin/editProject/index.html.twig', [
            'usersInProject' => $project->getUsers(),
            'locations' => $locations,
            'project' => $project,
            'questions' => $questions,
            'questionCountWithoutLocation' => $questionsWithoutLocation
        ]);
    }
}