<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Level;
use App\Entity\PlanningEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlanningEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanningEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanningEvent[]    findAll()
 * @method PlanningEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanningEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanningEvent::class);
    }

    public function findByCampusAndLevel(Campus $campus, Level $level, ?\DateTime $start = null, ?\DateTime $end = null)
    {
        $qb = $this->createQueryBuilder('pe')
            ->andWhere('pe.campus = :campus_id')
            ->andWhere('pe.level = :level_id')
            ->setParameters(['campus_id' => $campus->getId(), 'level_id' => $level->getId()])
        ;

        if ($start !== null) {
            $qb
                ->andWhere('pe.start >= :start OR pe.end >= :start')
                ->setParameter('start', $start)
            ;
        }
        
        if ($end !== null) {
            $qb
                ->andWhere('pe.start <= :end OR pe.end <= :end')
                ->setParameter('end', $end)
            ;
        }

        $result = $qb->getQuery()->getResult();

        return $result;
    }
}
