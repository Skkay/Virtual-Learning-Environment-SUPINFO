<?php

namespace App\Controller;

use App\Repository\GenderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/genders", name="app.gender.")
 */
class GenderController extends AbstractController
{
    private $genderRepository;

    public function __construct(GenderRepository $genderRepository)
    {
        $this->genderRepository = $genderRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $genders = $this->genderRepository->findAll();

        return $this->render('gender/index.html.twig', [
            'genders' => $genders,
        ]);
    }
}
