<?php

namespace App\Repository;

use App\Entity\Thesis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Thesis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thesis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thesis[]    findAll()
 * @method Thesis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThesisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thesis::class);
    }
}
