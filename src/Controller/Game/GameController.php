<?php

namespace App\Controller\Game;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use App\Form\Game\AnswerQuestionType;
use App\Repository\AnswerRepository;
use App\Repository\DeviceRepository;
use App\Repository\LocationRepository;
use App\Repository\ProjectRepository;
use App\Service\EncryptionService;
use App\Service\PhotoUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController extends AbstractController
{
    public function __construct(
        private DeviceRepository       $deviceRepository,
        private EntityManagerInterface $entityManager,
        private EncryptionService      $encryptionService,
        private AnswerRepository       $answerRepository,
        private LocationRepository     $locationRepository,
        private PhotoUploadService     $photoUploadService,
        private ProjectRepository      $projectRepository,
    )
    {}

    public function index($guid, Security $security, Request $request): Response
    {
        /* @var User $user */
        $user = $security->getUser();

        if ($user === null) {
            return $this->render('NotLoggedIn.html.twig');
        }

        $question = $this->deviceRepository->getQuestionByDevice($guid, $user);

        if (!$question instanceof Question) {
            $this->addFlash('game_error', $question);
            return $this->redirectToRoute('game_question_not_found');
        }

        $answer = $this->answerRepository->findOneBy(['user' => $user->getId(), 'question' => $question->getId()]);
        if ($answer !== null) {
            return $this->redirectToRoute('game_already_answered');
        }

        $form = $this->createForm(AnswerQuestionType::class, null, [
            'question_id' => $question->getId(),
            'question_type' => $question->getType()
        ]);

        $form->handleRequest($request);

        // set lastQuestion on user to protect multiple scanning multiple
        if($user->getLastOpenedQuestion() !== null){
            if($question->getId() !== $user->getLastOpenedQuestion()->getId())
            {
                return $this->redirectToRoute('game_answer_previous_first');
            }
        }

        $user->setLastOpenedQuestion($question);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle form submission and processing here

            $answer = new Answer();

            $answer->setUser($user);
            $answer->setQuestion($question);

            switch ($question->getType()) {
                case Question::MULTI_QUESTION_TYPE:
                    $selectedAnswer = str_replace("multi", "", $this->encryptionService->decryptData($form->getData()['answer']['selected_answer']));
                    $answer->setMultiAnswer($selectedAnswer);
                    if ($selectedAnswer === "1") {
                        $answer->setPoints($question->getPoints());
                    } else {
                        $answer->setPoints(0);
                    }
                    break;
                case Question::OPEN_QUESTION_TYPE:
                    $answer->setOpen($form->getData()['answer']['open']);
                    break;
                case Question::PHOTO_QUESTION_TYPE:
                    $image = $form->getData()['answer']['image'];
                    if($image !== null) {
                        if (($image->getClientOriginalName() !== $question->getImage())) {
                            $newFilename = $this->photoUploadService->AddPhotoFile($image, $this->getParameter('answer_images'));
                            $answer->setImage($newFilename);
                        }
                    }
                default:
            }

            /** @var User $user */
            $user = $this->getUser();
            if ($user === null) {
                return $this->redirectToRoute('Home');
            }
            $project = $user->getProject();
            if ($project === null) {
                return $this->redirectToRoute('Home');
            }
            $this->entityManager->persist($answer);
            $this->entityManager->flush();

            $unAnsweredLocations = $this->locationRepository->findUnansweredLocationsByUserAndProject($user->getId(), $project->getId(), $answer);

            if (count($unAnsweredLocations) === 0) {
                return $this->redirectToRoute('game_answered_question', [
                    'question' => $question->getId(),
                ]);
            }

            $randomLocation = array_rand($unAnsweredLocations);
            $answer->setReceivedRandomLocation($unAnsweredLocations[$randomLocation]);

            $this->entityManager->persist($answer);
            $this->entityManager->flush();

            return $this->redirectToRoute('game_answered_question', [
                'question' => $question->getId(),
            ]);
        }

        return $this->render('game/index.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    public function viewAnswers($project, Security $security): Response
    {

        $questions = $this->projectRepository->getAnsweredQuestionsForUserInProject($project, $security->getUser());
        $project = $this->projectRepository->find($project);

        return $this->render('game/view_answers.html.twig', [
            'questions' => $questions,
            'project' => $project
        ]);
    }

    public function viewAnswersDetail($project, $answer, Security $security): Response
    {
        $answer = $this->answerRepository->find($answer);

        if($answer->getUser() !== $security->getUser()) {
            return $this->redirectToRoute('Home');
        }

        $question = $answer->getQuestion();
        $project = $this->projectRepository->find($project);

        return $this->render('game/view_answers_detail.html.twig', [
            'project' => $project,
            'question' => $question,
            'answer' => $answer
            
        ]);
    }

    public function answered(Question $question): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute('Home');
        }

        //reset lastOpenedQuestion
        $user->setLastOpenedQuestion(null);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        /** @var Answer $answer */
        $answer = $this->answerRepository->findOneBy(['user' => $user->getId(), 'question' => $question->getId()]);
        $hintLocation = $answer?->getReceivedRandomLocation();

        $answerCorrect = null;
        $locationHint = null;
        $questionType = null;
        $device = null;
        switch ($question->getType()) {
            case Question::MULTI_QUESTION_TYPE:
                $questionType = Question::MULTI_QUESTION_TYPE;
                if ($answer->getMultiAnswer() === 1) {
                    $answerCorrect = true;
                } else {
                    $answerCorrect = false;
                }
                break;
            case Question::OPEN_QUESTION_TYPE:
                $questionType = Question::OPEN_QUESTION_TYPE;
                break;
            case Question::PHOTO_QUESTION_TYPE:
                $questionType = Question::PHOTO_QUESTION_TYPE;
                break;
        }

        if (!$hintLocation === null) {
            $device = $hintLocation->getDevice();
            $locationHint = $hintLocation->getCoordinateHint();

        }

        if($answerCorrect === true){
            $hint = $question->getHint();
        }

        return $this->render('game/answered_question.html.twig', [
            'locationHint' => $locationHint,
            'answerCorrect' => $answerCorrect,
            'device' => $device,
            'questionType' => $questionType,
            'hint' => $hint ?? null
        ]);
    }

    public function QuestionNotFoundAction(): Response
    {
        return $this->render('game/question_not_found.html.twig');
    }

    public function alreadyAnswered(): Response
    {
        return $this->render('game/question_already_answered.html.twig');
    }

    public function answerPreviousFirst(): Response
    {
        return $this->render('game/answer_previous_question_first.twig');
    }
}