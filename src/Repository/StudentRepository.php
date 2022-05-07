<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Company;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    /**
     * Returns number of student hired per company if $hired == true or in training contract per company otherwise
     */
    public function countNbOfStudentsInCompanyByCampus(Campus $campus, bool $hired)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('COUNT(s.id) AS count')
            ->addSelect('c.name AS company_name')
            ->andWhere('s.campus = :campus_id')
            ->setParameters(['campus_id' => $campus->getId()])
            ->groupBy('c.id')
        ;

        if ($hired) {
            $qb->leftJoin('s.companyHired', 'c');
        } else {
            $qb->leftJoin('s.companyTrainingContract', 'c');
        }

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * Returns students list hired if $hired == true or in training contract otherwise, by campus and company.
     */
    public function findByCampusAndCompany(Campus $campus, Company $company, bool $hired)
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('c.id = :company_id')
            ->andWhere('s.campus = :campus_id')
            ->setParameters([
                'company_id' => $company->getId(),
                'campus_id' => $campus->getId()
            ])
        ;

        if ($hired) {
            $qb->leftJoin('s.companyHired', 'c');
        } else {
            $qb->leftJoin('s.companyTrainingContract', 'c');
        }

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function countActiveStudentsInCampus()
    {
        $qb = $this->createQueryBuilder('s')
            ->select('COUNT(s.id) AS count')
            ->addSelect('c.label AS campus_label')
            ->andWhere('s.level IS NOT NULL')
            ->leftJoin('s.campus', 'c')
            ->groupBy('c.id')
        ;

        $result = $qb->getQuery()->getResult();

        return $result;
    }
}
