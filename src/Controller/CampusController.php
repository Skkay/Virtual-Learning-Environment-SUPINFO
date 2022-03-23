<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/campus", name="app.campus.")
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

        return $this->render('campus/index.html.twig', [
            'campus' => $campus,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Campus $campus): Response
    {
        return $this->render('campus/show.html.twig', [
            'campus' => $campus,
        ]);
    }
}
