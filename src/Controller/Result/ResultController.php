<?php

namespace App\Controller\Result;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ResultService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ResultController extends AbstractController
{
    public function __construct(private UserRepository $userRepository, private ResultService $resultService)
    {
    }

    public function index(Project $project = null): Response
    {
        /**
         * fix voor entity not found by @paramConverter annotation
         */
        if($project === null)
        {
            return $this->redirectToRoute('Home');
        }
        $questions = $project->getQuestions();
        $users = $this->userRepository->getUsersInProject($project->getId());
        $userArray = [];
        /** @var User $user */
        foreach ($users as $user) {
            $userArray[] = $user->getUsername();
        }
        $resultArray = $this->resultService->getAllResultsForUsers($questions);
        $totalResultArray = $this->resultService->getTotalResultForUsers($project, $users);

        return $this->render('/Result/index.html.twig', [
            'project' => $project,
            'userArray' => $userArray,
            'resultArray' => $resultArray,
            'totalResultArray' => $totalResultArray
        ]);
    }

}