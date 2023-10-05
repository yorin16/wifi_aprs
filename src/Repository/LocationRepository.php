<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Location;
use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Location>
 *
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function save(Location $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Location $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findUnansweredLocationsByUserAndProject($userId, $projectId, Answer $answer)
    {
        /** @var AnswerRepository $answerRepository */
//        $answerRepository = $this->getEntityManager()->getRepository(Answer::class);
        $answersByUser = $this->getEntityManager()->createQueryBuilder()
            ->select('a')
            ->from('App:Answer', 'a')
            ->leftJoin('a.question', 'q')
            ->where('q.Project = :projectId')
            ->andWhere('a.user = :userId')
            ->setParameter('projectId', $projectId)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();

        $answerLocations = [];
        $locations = [];
        foreach ($answersByUser as $answer) {
            $answerLocations[] = $answer->getQuestion()->getLocation();
        }

        /** @var LocationRepository $locationsRepository */
        $locationsRepository = $this->getEntityManager()->getRepository(Location::class);
        $partValidLocations = $this->getEntityManager()->createQueryBuilder()
            ->select('l')
            ->from(Location::class, 'l')
            ->join('l.question', 'q')
            ->where('l.Project = :projectId')
            ->andWhere('q.id IS NOT NULL')
            ->setParameter('projectId', $projectId)
            ->getQuery()
            ->execute();

        foreach ($partValidLocations as $partValidLocation) {
            if ($partValidLocation->getQuestion() !== null) {
                $locations[] = $partValidLocation;
            }
        }

        /** @var Location $location */
        $answerLocationIds = array_map(function ($location) {
            return $location->getId();
        }, $answerLocations);

// Filtering $locations array
        /** @var Location $location */
        $filteredLocations = array_filter($locations, function ($location) use ($answerLocationIds) {
            // Check if the location's ID exists in $answerLocationIds
            return !in_array($location->getId(), $answerLocationIds);
        });


        return $filteredLocations;

    }

}
