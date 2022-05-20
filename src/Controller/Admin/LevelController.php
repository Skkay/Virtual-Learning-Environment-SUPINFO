<?php

namespace App\Controller\Admin;

use App\Entity\Level;
use App\Repository\LevelRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/levels", name="app.admin.level.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class LevelController extends AbstractController
{
    private $levelRepository;

    public function __construct(LevelRepository $levelRepository)
    {
        $this->levelRepository = $levelRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $levels = $this->levelRepository->findAll();

        return $this->render('admin/level/index.html.twig', [
            'levels' => $levels,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Level $level): Response
    {
        return $this->render('admin/level/show.html.twig', [
            'level' => $level,
        ]);
    }
}
