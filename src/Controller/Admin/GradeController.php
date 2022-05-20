<?php

namespace App\Controller\Admin;

use App\Entity\Grade;
use App\Repository\GradeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/grades", name="app.admin.grade.")
 * @Security("is_granted('ROLE_ADMIN')")
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

        return $this->render('admin/grade/index.html.twig', [
            'grades' => $grades,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Grade $grade): Response
    {
        return $this->render('admin/grade/show.html.twig', [
            'grade' => $grade,
        ]);
    }
}
