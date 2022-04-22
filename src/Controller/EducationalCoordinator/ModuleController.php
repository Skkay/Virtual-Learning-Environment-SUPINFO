<?php

namespace App\Controller\EducationalCoordinator;

use App\Repository\ModuleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/educational_coordinator/modules", name="app.educational_coordinator.module.")
 * @Security("is_granted('ROLE_EDUCATIONAL_COORDINATOR')")
 */
class ModuleController extends AbstractController
{
    private $moduleRepository;

    public function __construct(ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $modules = $this->moduleRepository->findAll();

        return $this->render('educational_coordinator/module/index.html.twig', [
            'modules' => $modules,
        ]);
    }
}
