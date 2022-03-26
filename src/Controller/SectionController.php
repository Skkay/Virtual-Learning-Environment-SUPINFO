<?php

namespace App\Controller;

use App\Entity\Section;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sections", name="app.section.")
 */
class SectionController extends AbstractController
{
    private $sectionRepository;

    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $sections = $this->sectionRepository->findALl();

        return $this->render('section/index.html.twig', [
            'sections' => $sections,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Section $section): Response
    {
        return $this->render('section/show.html.twig', [
            'section' => $section,
        ]);
    }
}
