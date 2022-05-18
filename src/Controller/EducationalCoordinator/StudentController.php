<?php

namespace App\Controller\EducationalCoordinator;

use App\Entity\Grade;
use App\Entity\Module;
use App\Entity\Student;
use App\Repository\GradeRepository;
use App\Repository\ModuleRepository;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/educational_coordinator/students", name="app.educational_coordinator.student.")
 * @Security("is_granted('ROLE_EDUCATIONAL_COORDINATOR')")
 */
class StudentController extends AbstractController
{
    private $em;

    /** @var StudentRepository */
    private $studentRepository;

    /** @var ModuleRepository */
    private $moduleRepository;

    /** @var GradeRepository */
    private $gradeRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->studentRepository = $this->em->getRepository(Student::class);
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

        $students = $this->studentRepository->findBy(['campus' => $staff->getCampus()]);

        return $this->render('educational_coordinator/student/index.html.twig', [
            'students' => $students,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Student $student): Response
    {
        $modules = $this->moduleRepository->findAllOrderedByLevel();

        $grades = $this->gradeRepository->findBy(['student' => $student]);
        foreach ($grades as $grade) {
            if ($grade->getGrade() !== null) {
                $grades[$grade->getModule()->getLabel()] = $grade->getGrade();
            }
        }

        return $this->render('educational_coordinator/student/show.html.twig', [
            'student' => $student,
            'modules' => $modules,
            'grades' => $grades,
        ]);
    }
}
