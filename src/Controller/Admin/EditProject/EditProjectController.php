<?php

namespace App\Controller\Admin\EditProject;

use App\Entity\Location;
use App\Entity\User;
use App\Repository\AnswerRepository;
use App\Repository\ProjectRepository;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use App\Service\ResultService;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EditProjectController extends AbstractController
{
    public function __construct(private ProjectRepository  $projectRepository,
                                private UserRepository     $userRepository,
                                private ResultService $resultService)
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
        $locationsWithoutQuestions = $this->projectRepository->CountLocationsInProjectWithoutQuestions($project->getId());

        $userArray = [];

        $users = $this->userRepository->getUsersInProject($project->getId());

        /** @var User $user */
        foreach ($users as $user) {
            $userArray[] = $user->getUsername();
        }

        $resultArray = $this->resultService->getAllResultsForUsers($questions, $users);
        $totalResultArray = $this->resultService->getTotalResultForUsers($project, $users);

        return $this->render('admin/editProject/index.html.twig', [
            'usersInProject' => $project->getUsers(),
            'locations' => $locations,
            'project' => $project,
            'questions' => $questions,
            'questionCountWithoutLocation' => $questionsWithoutLocation,
            'locationCountWithoutQuestion' => $locationsWithoutQuestions,
            'userArray' => $userArray,
            'resultArray' => $resultArray,
            'totalResultArray' => $totalResultArray
        ]);
    }




}