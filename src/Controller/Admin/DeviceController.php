<?php

namespace App\Controller\Admin;

use App\Entity\Device;
use App\Form\Device\DeviceCreateType;
use App\Form\Device\DeviceEditType;
use App\Repository\DeviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    public function edit(Request $request, Device $device): Response
    {
        $form = $this->createForm(DeviceEditType::class, $device);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($device);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_device');
        }

        return $this->render('admin/device/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function delete(Device $device, Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete_device_' . $device->getId(), $request->request->get('_token'))) {
            $entityManager->remove($device);
            $entityManager->flush();
            $this->addFlash('success', 'Device deleted');
        } else {
            $this->addFlash('error', 'Invalid CSRF token');
        }

        return $this->redirectToRoute('admin_device');
    }

    public function confirmDelete(Device $device): Response
    {
        return $this->render('admin/device/confirm_delete.html.twig', [
            'device' => $device,
        ]);
    }


}