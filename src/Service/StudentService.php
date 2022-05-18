<?php

namespace App\Service;

use App\Entity\Level;
use App\Entity\Student;
use App\Repository\LevelRepository;
use Doctrine\Persistence\ManagerRegistry;

class StudentService
{
    private $em;

    /** @var LevelRepository */
    private $levelRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine;
        $this->levelRepository = $this->em->getRepository(Level::class);
    }

    public function getTotalEcts(Student $student)
    {
        // Add 60 ECTS per year before entry level
        $ects = ($student->getEntryLevel()->getNumericLevel() - 1) * 60;

        foreach ($student->getGrades() as $grade) {
            if ($grade->getGrade() !== null && $grade->getGrade() >= 10) {
                $ects += $grade->getModule()->getEcts();
            }
        }

        return [
            'currentEcts' => $ects,
            'currentNeededEcts' => $student->getLevel()->getNumericLevel() * 60,
            'maxEcts' => count($this->levelRepository->findAll()) * 60,
            'maxNeededEcts' => 60 * 5,
        ];
    }
}