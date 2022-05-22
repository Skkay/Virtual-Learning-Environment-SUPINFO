<?php

namespace App\Controller\Admin;

use App\Entity\Diploma;
use App\Repository\DiplomaRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/diplomas", name="app.admin.diploma.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class DiplomaController extends AbstractController
{
    private $diplomaRepository;

    public function __construct(DiplomaRepository $diplomaRepository)
    {
        $this->diplomaRepository = $diplomaRepository;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $diplomas = $this->diplomaRepository->findAll();

        return $this->render('admin/diploma/index.html.twig', [
            'diplomas' => $diplomas,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Diploma $diploma): Response
    {
        return $this->render('admin/diploma/show.html.twig', [
            'diploma' => $diploma,
        ]);
    }
}
