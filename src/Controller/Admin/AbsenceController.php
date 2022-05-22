<?php

namespace App\Controller\Admin;

use App\Entity\Absence;
use App\Repository\AbsenceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/absence", name="app.admin.absence.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AbsenceController extends AbstractController
{
    private $absenceRepository;

    public function __construct(AbsenceRepository $absenceRepository)
    {
        $this->absenceRepository = $absenceRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $absences = $this->absenceRepository->findAll();

        return $this->render('admin/absence/index.html.twig', [
            'absences' => $absences,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Absence $absence): Response
    {
        return $this->render('admin/absence/show.html.twig', [
            'absence' => $absence,
        ]);
    }
}
