<?php

namespace App\Service;

use App\Entity\Answer;
use App\Entity\Project;
use App\Entity\Question;
use App\Entity\User;
use App\Repository\AnswerRepository;

class ResultService
{
    public function __construct(private AnswerRepository $answerRepository)
    {
    }

    public function getAllResultsForUsers($questions): array
    {
        $resultArray = [];

        /** @var Question $question */
        foreach ($questions as $question) {
            /** @var Answer $score */
            $scores = $this->answerRepository->findBy(['question' => $question], ['user' => 'ASC']);

            $location = $question->getLocation();
            if ($location === null) {
                continue;
            }
            $device = $question->getLocation()->getDevice();
            if ($device === null) {
                continue;
            }

            if ($scores == null) {
                $results = [
                    'score' => null,
                    'type' => $question->getType(),
                    'user' => null,
                    'device' => $device,
                    'question' => $question
                ];

                $resultArray[$question->getId()][] = $results;
            } else {
                $resultArray[$question->getId()] = [];
                foreach ($scores as $score) {
                    $device = $score->getQuestion()->getLocation()->getDevice();

                    if ($device === null) {
                        continue;
                    }
                    $results = [
                        'score' => $score->getPoints(),
                        'type' => $question->getType(),
                        'user' => $score->getUser(),
                        'device' => $device,
                        'question' => $question
                    ];

                    $resultArray[$question->getId()][] = $results;
                }
            }
        }

        return $resultArray;
    }

    public function getTotalResultForUsers(Project $project, $users): array
    {
        $totalResultArray = [];

        /** @var User $user */
        foreach ($users as $user) {

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

            $totalResultArray[$user->getUsername()] = $sumPoints;

        }
        return $totalResultArray;
    }
}