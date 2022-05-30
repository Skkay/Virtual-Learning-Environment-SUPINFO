<?php

namespace App\Repository;

use App\Entity\DataSchema;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataSchema|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataSchema|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataSchema[]    findAll()
 * @method DataSchema[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataSchemaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataSchema::class);
    }
}
