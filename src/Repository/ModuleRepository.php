<?php

namespace App\Repository;

use App\Entity\Module;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Module|null find($id, $lockMode = null, $lockVersion = null)
 * @method Module|null findOneBy(array $criteria, array $orderBy = null)
 * @method Module[]    findAll()
 * @method Module[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    public function findAllOrderedByLevel()
    {
        $qb = $this->createQueryBuilder('m')
            ->leftJoin('m.level', 'l')
            ->orderBy('l.numericLevel', 'ASC')
            ->addOrderBy('l.label', 'ASC')
        ;

        $result = $qb->getQuery()->getResult();

        return $result;
    }
}
