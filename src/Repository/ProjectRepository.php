<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function save(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getFields(): array
    {
        $classMetadata = $this->getEntityManager()->getClassMetadata(Project::class);
        $fieldNames = $classMetadata->getFieldNames();

        return array_filter($fieldNames, function ($fieldName) use ($classMetadata) {
            $fieldType = $classMetadata->getTypeOfField($fieldName);

            return $fieldType !== 'datetime'
                && $fieldType !== 'datetimetz'
                && $fieldType !== 'time'
                && $fieldType !== 'date'
                && $fieldName !== 'id';
        });
    }

    public function findQuestionsByProjectId(int $projectId): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('q')
            ->from(Question::class, 'q')
            ->join('q.Location', 'l')
            ->join('l.Project', 'p')
            ->where('p.id = :projectId')
            ->setParameter('projectId', $projectId);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function CountQuestionsInProjectWithoutLocation(int $projectId): int
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        return $queryBuilder->select('count(q.id) as count')
                    ->from(Question::class, 'q')
            ->where('q.Project = :project_id')
            ->andWhere('q.Location IS NULL')
            ->setParameter('project_id', $projectId)
            ->getQuery()
            ->getSingleScalarResult();
    }

}
