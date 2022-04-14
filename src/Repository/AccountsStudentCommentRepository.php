<?php

namespace App\Repository;

use App\Entity\AccountsStudentComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccountsStudentComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountsStudentComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountsStudentComment[]    findAll()
 * @method AccountsStudentComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountsStudentCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountsStudentComment::class);
    }
}
