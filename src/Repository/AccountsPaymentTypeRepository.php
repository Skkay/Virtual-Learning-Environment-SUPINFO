<?php

namespace App\Repository;

use App\Entity\AccountsPaymentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccountsPaymentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountsPaymentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountsPaymentType[]    findAll()
 * @method AccountsPaymentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountsPaymentTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountsPaymentType::class);
    }
}
