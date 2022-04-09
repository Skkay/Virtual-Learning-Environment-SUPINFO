<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/accounts", name="app.accounts.")
 * @Security("is_granted('ROLE_ADMINISTRATION')")
 */
class AccountsController extends AbstractController
{
    private $em;

    /** @var StudentRepository */
    private $studentRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->studentRepository = $this->em->getRepository(Student::class);
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('accounts/index.html.twig', [
            'controller_name' => 'AccountsController',
        ]);
    }

    /**
     * @Route("/students", name="student.index")
     */
    public function indexStudent(): Response
    {
        $students = $this->studentRepository->findAll();

        return $this->render('accounts/student/index.html.twig', [
            'students' => $students,
        ]);
    }

    /**
     * @Route("/students/{id}", name="student.show")
     */
    public function showStudent(Student $student): Response
    {
        return $this->render('accounts/student/show.html.twig', [
            'student' => $student,
        ]);
    }
}
