<?php

namespace App\Repository;

use App\Entity\FieldEquivalence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FieldEquivalence|null find($id, $lockMode = null, $lockVersion = null)
 * @method FieldEquivalence|null findOneBy(array $criteria, array $orderBy = null)
 * @method FieldEquivalence[]    findAll()
 * @method FieldEquivalence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldEquivalenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FieldEquivalence::class);
    }
}
