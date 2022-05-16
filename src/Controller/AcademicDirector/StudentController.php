<?php

namespace App\Controller\AcademicDirector;

use App\Entity\Level;
use App\Entity\Student;
use App\Repository\LevelRepository;
use App\Repository\StudentRepository;
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

    /** @var StudentRepository */
    private $studentRepository;

    /** @var LevelRepository */
    private $levelRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->studentRepository = $this->em->getRepository(Student::class);
        $this->levelRepository = $this->em->getRepository(Level::class);
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

        return $this->render('academic_director/student/show.html.twig', [
            'student' => $student,
            'levels' => $levels,
        ]);
    }
}
