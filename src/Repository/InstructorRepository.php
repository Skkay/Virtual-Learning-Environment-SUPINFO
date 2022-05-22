<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Instructor;
use App\Entity\Module;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Instructor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Instructor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Instructor[]    findAll()
 * @method Instructor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstructorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instructor::class);
    }

    public function findByCampus(Campus $campus)
    {
        $qb = $this->createQueryBuilder('i')
            ->leftJoin('i.sections', 's')
            ->leftJoin('s.campus', 'c')
            ->andWhere('c.id = :campus_id')
            ->setParameters(['campus_id' => $campus->getId()])
        ;

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findByCampusAndModule(Campus $campus, Module $module)
    {
        $qb = $this->createQueryBuilder('i')
        ->leftJoin('i.sections', 's')
        ->leftJoin('s.campus', 'c')
        ->andWhere('c.id = :campus_id')
        ->leftJoin('i.modules', 'm')
        ->andWhere('m.id = :module_id')
        ->setParameters([
            'campus_id' => $campus->getId(),
            'module_id' => $module->getId()
        ]);

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findByModule(Module $module)
    {
        $qb = $this->createQueryBuilder('i')
            ->leftJoin('i.modules', 'm')
            ->andWhere('m.id = :module_id')
            ->setParameters([
                'module_id' => $module->getId()
            ])
        ;

        $query = $qb->getQuery();

        return $query->execute();
    }
}
