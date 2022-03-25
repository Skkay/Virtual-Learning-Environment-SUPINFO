<?php

namespace App\Controller;

use App\Repository\GradeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grades", name="app.grade.")
 */
class GradeController extends AbstractController
{
    private $gradeRepository;

    public function __construct(GradeRepository $gradeRepository)
    {
        $this->gradeRepository = $gradeRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $grades = $this->gradeRepository->findAll();

        return $this->render('grade/index.html.twig', [
            'grades' => $grades,
        ]);
    }
}
