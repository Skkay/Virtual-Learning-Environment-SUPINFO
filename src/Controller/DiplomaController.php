<?php

namespace App\Controller;

use App\Entity\Diploma;
use App\Repository\DiplomaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/diplomas", name="app.diploma.")
 */
class DiplomaController extends AbstractController
{
    private $diplomaRepository;

    public function __construct(DiplomaRepository $diplomaRepository)
    {
        $this->diplomaRepository = $diplomaRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $diplomas = $this->diplomaRepository->findAll();

        return $this->render('diploma/index.html.twig', [
            'diplomas' => $diplomas,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Diploma $diploma): Response
    {
        return $this->render('diploma/show.html.twig', [
            'diploma' => $diploma,
        ]);
    }
}
