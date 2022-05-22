<?php

namespace App\Controller\Admin;

use App\Entity\Section;
use App\Repository\SectionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sections", name="app.admin.section.")
 * @Security("is_granted('ROLE_ADMIN')")
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

        return $this->render('admin/section/index.html.twig', [
            'sections' => $sections,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Section $section): Response
    {
        return $this->render('admin/section/show.html.twig', [
            'section' => $section,
        ]);
    }
}
