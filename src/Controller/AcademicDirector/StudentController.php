<?php

namespace App\Controller\AcademicDirector;

use App\Entity\Grade;
use App\Entity\Level;
use App\Entity\Module;
use App\Entity\Student;
use App\Repository\GradeRepository;
use App\Repository\LevelRepository;
use App\Repository\ModuleRepository;
use App\Repository\StudentRepository;
use App\Service\StudentService;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/academic_director/students", name="app.academic_director.student.")
 * @Security("is_granted('ROLE_ACADEMIC_DIRECTOR')")
 */
class StudentController extends AbstractController
{
    private $em;
    private $studentService;

    /** @var StudentRepository */
    private $studentRepository;

    /** @var LevelRepository */
    private $levelRepository;

    /** @var ModuleRepository */
    private $moduleRepository;

    /** @var GradeRepository */
    private $gradeRepository;

    public function __construct(ManagerRegistry $doctrine, StudentService $studentService)
    {
        $this->em = $doctrine->getManager();
        $this->studentService = $studentService;
        $this->studentRepository = $this->em->getRepository(Student::class);
        $this->levelRepository = $this->em->getRepository(Level::class);
        $this->moduleRepository = $this->em->getRepository(Module::class);
        $this->gradeRepository = $this->em->getRepository(Grade::class);
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $staff = $user->getStaff();

        if ($staff === null) {
            throw new NotFoundHttpException('Current logged user is not a staff');
        }

        $students = $this->studentRepository->findAll();

        return $this->render('academic_director/student/index.html.twig', [
            'students' => $students,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Student $student): Response
    {
        $levels = $this->levelRepository->findAll();
        $ects = $this->studentService->getTotalEcts($student);
        $modules = $this->moduleRepository->findAllOrderedByLevel();

        $grades = $this->gradeRepository->findBy(['student' => $student]);
        foreach ($grades as $grade) {
            if ($grade->getGrade() !== null) {
                $grades[$grade->getModule()->getLabel()] = $grade->getGrade();
            }
        }

        return $this->render('academic_director/student/show.html.twig', [
            'student' => $student,
            'levels' => $levels,
            'studentEcts' => $ects,
            'modules' => $modules,
            'grades' => $grades,
        ]);
    }
}
