<?php

namespace App\Controller\Game;

use App\Entity\Answer;
use App\Entity\User;
use App\Form\Game\AnswerQuestionType;
use App\Repository\DeviceRepository;
use App\Service\EncryptionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController extends AbstractController
{
    public function __construct(
        private DeviceRepository $deviceRepository,
        private EntityManagerInterface $entityManager,
        private EncryptionService $encryptionService
    ){}

    //TODO: vraag blokeren waneer deze al is ingevuld door account

    public function index($guid, Security $security, Request $request): Response
    {
        /* @var User $user */
        $user = $security->getUser();

        if($user === null)
        {
            return $this->render('NotLoggedIn.html.twig');
        }

        $question = $this->deviceRepository->getQuestionByDevice($guid, $user);

        $form = $this->createForm(AnswerQuestionType::class, null, [
            'question_id' => $question->getId(),
            'question_type' => $question->getType()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle form submission and processing here

            $answer = new Answer();

            $answer->setUser($user);
            $answer->setQuestion($question);

            switch ($question->getType()){
                case 1: //Multiple choice
                    $selectedAnswer = str_replace("multi","",$this->encryptionService->decryptData($form->getData()['answer']['selected_answer']));
                    $answer->setMultiAnswer($selectedAnswer);
                    $answer->setPoints($question->getPoints());
                    break;
                case 2: //Open
                    $answer->setOpen($form->getData()['answer']['open']);
                    break;
                default:
            }

            $this->entityManager->persist($answer);
            $this->entityManager->flush();

           return $this->redirectToRoute('game_answered_question', [
                'question' =>  $question->getId()
            ]);
        }

        return $this->render('game/index.html.twig',[
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    public function answered(): Response
    {
        return $this->render('game/answered_question.html.twig');
    }
}