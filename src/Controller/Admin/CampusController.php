<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use App\Repository\CampusRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/campus", name="app.admin.campus.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class CampusController extends AbstractController
{
    private $campusRepository;

    public function __construct(CampusRepository $campusRepository)
    {
        $this->campusRepository = $campusRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $campus = $this->campusRepository->findAll();

        return $this->render('admin/campus/index.html.twig', [
            'campus' => $campus,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Campus $campus): Response
    {
        return $this->render('admin/campus/show.html.twig', [
            'campus' => $campus,
        ]);
    }
}
