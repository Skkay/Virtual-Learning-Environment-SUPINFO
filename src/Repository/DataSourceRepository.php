<?php

namespace App\Repository;

use App\Entity\DataSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataSource[]    findAll()
 * @method DataSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataSourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataSource::class);
    }
}
