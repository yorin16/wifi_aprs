<?php

namespace App\Controller\Admin;

use App\Entity\Device;
use App\Form\Device\DeviceCreateType;
use App\Repository\DeviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeviceController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DeviceRepository       $deviceRepository
    )
    {
    }

    public function index(): Response
    {

        $fields = $this->deviceRepository->getFields();
        $devices = $this->deviceRepository->findAll();

        return $this->render('admin/device/index.html.twig', [
            'fields' => $fields,
            'devices' => $devices
        ]);
    }

    public function add(Request $request): Response
    {
        $device = new Device();

        $device->setGuid(Uuid::uuid4()->toString());

        $form = $this->createForm(DeviceCreateType::class, $device);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($device);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_device');
        }

        return $this->render('admin/device/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}