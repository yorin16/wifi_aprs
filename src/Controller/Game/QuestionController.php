<?php

namespace App\Controller\Game;

use App\Entity\Device;
use App\Entity\Location;
use App\Entity\User;
use App\Repository\DeviceRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends AbstractController
{
    public function __construct(
        private DeviceRepository $deviceRepository,
        private LocationRepository $locationRepository,
    )
    {
    }

    /**
     * @throws ORMException
     */
    public function index($guid, Security $security): Response
    {
        /* @var User $user */
        $user = $security->getUser();

        if($user === null)
        {
            return $this->render('NotLoggedIn.html.twig');
        }

        $selectedProject = $user->getProject();

        /* @var Device $device */
        $device = $this->deviceRepository->findOneBy(['guid' => $guid]);

        /* @var Location $location */
        $location = $this->locationRepository->findOneBy(['Device' => $device, 'Project' => $selectedProject]);

        $question = $location->getQuestion();

        return $this->render('game/index.html.twig',[
            'question' => $question
        ]);
    }
}