<?php

namespace App\Controller;

use App\Repository\SpecialityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/specialities", name="app.speciality.")
 */
class SpecialityController extends AbstractController
{
    private $specialityRepository;

    public function __construct(SpecialityRepository $specialityRepository)
    {
        $this->specialityRepository = $specialityRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $specialities = $this->specialityRepository->findAll();

        return $this->render('speciality/index.html.twig', [
            'specialities' => $specialities,
        ]);
    }
}
