<?php

namespace App\Controller;

use App\Entity\Instructor;
use App\Repository\InstructorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/instructors", name="app.instructor.")
 */
class InstructorController extends AbstractController
{
    private $instructorRepository;

    public function __construct(InstructorRepository $instructorRepository)
    {
        $this->instructorRepository = $instructorRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $instructors = $this->instructorRepository->findAll();

        return $this->render('instructor/index.html.twig', [
            'instructors' => $instructors,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Instructor $instructor): Response
    {
        return $this->render('instructor/show.html.twig', [
            'instructor' => $instructor,
        ]);
    }
}
