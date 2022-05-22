<?php

namespace App\Controller\Admin;

use App\Entity\Thesis;
use App\Repository\ThesisRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/theses", name="app.admin.thesis.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class ThesisController extends AbstractController
{
    private $thesisRepository;

    public function __construct(ThesisRepository $thesisRepository)
    {
        $this->thesisRepository = $thesisRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $theses = $this->thesisRepository->findAll();

        return $this->render('admin/thesis/index.html.twig', [
            'theses' => $theses,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Thesis $thesis): Response
    {
        return $this->render('admin/thesis/show.html.twig', [
            'thesis' => $thesis,
        ]);
    }
}
