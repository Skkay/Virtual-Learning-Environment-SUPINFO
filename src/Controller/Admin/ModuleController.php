<?php

namespace App\Controller\Admin;

use App\Entity\Module;
use App\Repository\ModuleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/modules", name="app.admin.module.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class ModuleController extends AbstractController
{
    private $moduleRepository;

    public function __construct(ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $modules = $this->moduleRepository->findAllOrderedByLevel();

        return $this->render('admin/module/index.html.twig', [
            'modules' => $modules,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Module $module): Response
    {
        return $this->render('admin/module/show.html.twig', [
            'module' => $module,
        ]);
    }
}
