<?php

namespace App\Controller\AcademicDirector;

use App\Entity\Grade;
use App\Entity\Instructor;
use App\Entity\Module;
use App\Entity\Student;
use App\Repository\GradeRepository;
use App\Repository\InstructorRepository;
use App\Repository\ModuleRepository;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/academic_director/modules", name="app.academic_director.module.")
 * @Security("is_granted('ROLE_ACADEMIC_DIRECTOR')")
 */
class ModuleController extends AbstractController
{
    private $em;

    /** @var ModuleRepository */
    private $moduleRepository;

    /** @var StudentRepository */
    private $studentRepository;

    /** @var GradeRepository */
    private $gradeRepository;

    /** @var InstructorRepository */
    private $instructorRepository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->em = $managerRegistry->getManager();
        $this->moduleRepository = $this->em->getRepository(Module::class);
        $this->studentRepository = $this->em->getRepository(Student::class);
        $this->gradeRepository = $this->em->getRepository(Grade::class);
        $this->instructorRepository = $this->em->getRepository(Instructor::class);
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $modules = $this->moduleRepository->findAll();

        return $this->render('academic_director/module/index.html.twig', [
            'modules' => $modules,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Module $module): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $staff = $user->getStaff();

        if ($staff === null) {
            throw new NotFoundHttpException('Current logged user is not a staff');
        }

        $instructors = $this->instructorRepository->findByModule($module);
        $students = $this->studentRepository->findAll();
        $moduleGrades = $this->gradeRepository->findBy(['module' => $module]);

        // Set user identifier as grade key
        foreach ($moduleGrades as $key => $grade) {
            $moduleGrades[$grade->getStudent()->getUser()->getUserIdentifier()] = $grade;
            unset($moduleGrades[$key]);
        }

        return $this->render('academic_director/module/show.html.twig', [
            'module' => $module,
            'instructors' => $instructors,
            'students' => $students,
            'grades' => $moduleGrades,
        ]);
    }
}
