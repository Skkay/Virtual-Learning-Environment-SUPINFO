<?php

namespace App\Controller;

use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/students", name="app.student.")
 */
class StudentController extends AbstractController
{
    private $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;        
    }
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $students = $this->studentRepository->findAll();

        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }
}
