<?php

namespace App\Repository;

use App\Entity\Device;
use App\Entity\Location;
use App\Entity\Question;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Device>
 *
 * @method Device|null find($id, $lockMode = null, $lockVersion = null)
 * @method Device|null findOneBy(array $criteria, array $orderBy = null)
 * @method Device[]    findAll()
 * @method Device[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceRepository extends ServiceEntityRepository
{
    private LocationRepository $locationRepository;

    public function __construct(ManagerRegistry $registry, LocationRepository $locationRepository)
    {
        parent::__construct($registry, Device::class);
        $this->locationRepository = $locationRepository;
    }

    public function save(Device $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Device $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getFields(): array
    {
        $classMetadata = $this->getEntityManager()->getClassMetadata(Device::class);
        $fieldNames = $classMetadata->getFieldNames();

        return array_filter($fieldNames, function ($fieldName) use ($classMetadata) {
            $fieldType = $classMetadata->getTypeOfField($fieldName);

            return $fieldType !== 'datetime'
                && $fieldType !== 'datetimetz'
                && $fieldType !== 'time'
                && $fieldType !== 'date'
                && $fieldType !== 'guid'
                && $fieldType !== 'disabled'
                && $fieldName !== 'id';
        });
    }

    public function getQuestionByDevice(string $guid, User $user): string|Question
    {
        /* @var Device $device */
        $device = $this->findOneBy(['guid' => $guid]);

        if($device === null)
        {
            return 'Device not found';
        }

        $selectedProject = $user->getProject();

        if($selectedProject === null)
        {
            return 'No project selected for user';
        }

        /* @var Location $location */
        $location = $this->locationRepository->findOneBy(['Device' => $device, 'Project' => $selectedProject]);

        if($location === null)
        {
            return 'No location set for device';
        }

        if($location->getQuestion() === null)
        {
            return 'No Question for device';
        }

        return $location->getQuestion();
    }



}
