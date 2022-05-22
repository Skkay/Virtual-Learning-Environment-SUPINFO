<?php

namespace App\Controller\Admin;

use App\Entity\Instructor;
use App\Repository\InstructorRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/instructors", name="app.admin.instructor.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class InstructorController extends AbstractController
{
    private $instructorRepository;

    public function __construct(InstructorRepository $instructorRepository)
    {
        $this->instructorRepository = $instructorRepository;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $instructors = $this->instructorRepository->findAll();

        return $this->render('admin/instructor/index.html.twig', [
            'instructors' => $instructors,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Instructor $instructor): Response
    {
        return $this->render('admin/instructor/show.html.twig', [
            'instructor' => $instructor,
        ]);
    }
}
