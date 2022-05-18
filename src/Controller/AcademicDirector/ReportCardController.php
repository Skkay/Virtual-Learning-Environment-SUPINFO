<?php

namespace App\Controller\AcademicDirector;

use App\Entity\Grade;
use App\Entity\Level;
use App\Entity\Module;
use App\Entity\Student;
use App\Repository\GradeRepository;
use App\Repository\ModuleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/academic_director/report_card", name="app.academic_director.report_card.")
 * @Security("is_granted('ROLE_ACADEMIC_DIRECTOR')")
 */
class ReportCardController extends AbstractController
{
    private $em;

    /** @var ModuleRepository */
    private $moduleRepository;

    /** @var GradeRepository */
    private $gradeRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->moduleRepository = $this->em->getRepository(Module::class);
        $this->gradeRepository = $this->em->getRepository(Grade::class);
    }

    /**
     * @Route("/{student}/{level}", name="show")
     */
    public function show(Student $student, Level $level): Response
    {
        if ($student === null) {
            throw new NotFoundHttpException('Current logged user is not a student');
        }

        $modules = $this->moduleRepository->findByLevelOrderedByLevel($level);

        $grades = $this->gradeRepository->findBy(['student' => $student]);
        foreach ($grades as $grade) {
            if ($grade->getGrade() !== null) {
                $grades[$grade->getModule()->getLabel()] = $grade->getGrade();
            }
        }

        return $this->render('report_card.html.twig', [ // todo: changer path
            'student' => $student,
            'level' => $level,
            'modules' => $modules,
            'grades' => $grades,
        ]);
    }
}
