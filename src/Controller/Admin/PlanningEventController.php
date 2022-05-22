<?php

namespace App\Controller\Admin;

use App\Entity\PlanningEvent;
use App\Repository\PlanningEventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/planning_events", name="app.admin.planningEvent.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class PlanningEventController extends AbstractController
{
    private $planningEventRepository;

    public function __construct(PlanningEventRepository $planningEventRepository)
    {
        $this->planningEventRepository = $planningEventRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $planningEvents = $this->planningEventRepository->findAll();

        return $this->render('admin/planning_event/index.html.twig', [
            'planningEvents' => $planningEvents,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(PlanningEvent $planningEvent): Response
    {
        return $this->render('admin/planning_event/show.html.twig', [
            'planningEvent' => $planningEvent,
        ]);
    }
}
