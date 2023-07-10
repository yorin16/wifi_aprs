<?php

namespace App\Controller\Game;

use App\Entity\User;
use App\Repository\DeviceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

class GameController extends AbstractController
{
    public function __construct(
        private DeviceRepository $deviceRepository,
    ){}

    //TODO: vraag blokeren waneer deze al is ingevuld door account
    //TODO: antwoord selecteren, donkerder maken. id opslaan in hidden variable
    //TODO: beantwoord knop toevoegen. die naar bedankt pagina gaat

    public function index($guid, Security $security): Response
    {
        /* @var User $user */
        $user = $security->getUser();

        if($user === null)
        {
            return $this->render('NotLoggedIn.html.twig');
        }

        $question = $this->deviceRepository->getQuestionByDevice($guid, $user);

        return $this->render('game/index.html.twig',[
            'question' => $question
        ]);
    }
}