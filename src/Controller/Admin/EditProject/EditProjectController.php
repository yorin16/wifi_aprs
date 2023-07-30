<?php

namespace App\Controller\Admin\EditProject;

use App\Entity\Answer;
use App\Entity\Location;
use App\Entity\Project;
use App\Entity\User;
use App\Repository\AnswerRepository;
use App\Repository\ProjectRepository;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
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
                                private QuestionRepository $questionRepository,
                                private AnswerRepository   $answerRepository,
                                private EntityManagerInterface $entityManager)
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

        $resultArray = $this->getAllResultsForUsers($questions);
        $totalResultArray = $this->getTotalResultForUsers($project, $users);

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

    private function getAllResultsForUsers($questions): array
    {
        $resultArray = [];

        foreach ($questions as $question) {
            /** @var Answer $score */
            $scores = $this->answerRepository->findBy(['question' => $question], ['user' => 'ASC']);

            if ($scores == null) {
                $resultArray[$question->getId()] = [];
            } else {
                $resultArray[$question->getId()] = [];
                foreach ($scores as $score) {

                    $test = [
                        'score' => $score->getPoints(),
                        'type' => $question->getType(),
                        'user' => $score->getUser(),
                    ];

                    $resultArray[$question->getId()][] = $test;
                }
            }
        }

        return $resultArray;
    }

    private function getTotalResultForUsers(Project $project, $users): array
    {
        $totalResultArray = [];
//
        /** @var User $user */
        foreach($users as $user) {

            $sumPoints = 0;

            // Loop through the user's answers related to the project's questions
            foreach ($user->getAnswers() as $answer) {
                $question = $answer->getQuestion();

                // Check if the answer belongs to a question within the specified project
                if ($question && $question->getProject() === $project) {
                    // Add the points for this answer to the sum
                    $sumPoints += $answer->getPoints() ?? 0;
                }
            }

            $totalResultArray[$user->getUsername()] =  $sumPoints;

        }
        return $totalResultArray;
    }
}