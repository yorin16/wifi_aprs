<?php

namespace App\Controller\Game;

use App\Entity\Answer;
use App\Entity\User;
use App\Form\Game\AnswerQuestionType;
use App\Repository\DeviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController extends AbstractController
{
    public function __construct(
        private DeviceRepository $deviceRepository,
        private EntityManagerInterface $entityManager
    ){}

    //TODO: vraag blokeren waneer deze al is ingevuld door account
    //TODO: antwoord selecteren, donkerder maken. id opslaan in hidden variable
    //TODO: beantwoord knop toevoegen. die naar bedankt pagina gaat

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
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle form submission and processing here

            $answer = new Answer();

            $answer->setUser($user);
            $answer->setQuestion($question);

            switch ($question->getType()){
                case 1:
                    $answer->setMultiAnswer($form->get('selected_answer')->getData());
                    $answer->setPoints($question->getPoints());
                    break;
                case 2:
//                    $answer->setOpen();
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