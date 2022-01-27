<?php

namespace App\Repository;

use App\Entity\CompanyTrainingContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompanyTrainingContract|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyTrainingContract|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyTrainingContract[]    findAll()
 * @method CompanyTrainingContract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyTrainingContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyTrainingContract::class);
    }
}
