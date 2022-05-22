<?php

namespace App\Controller\EducationalCoordinator;

use App\Entity\PlanningEvent;
use App\Repository\PlanningEventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/educational_coordinator/planning_events", name="app.educational_coordinator.planning_events.")
 * @Security("is_granted('ROLE_EDUCATIONAL_COORDINATOR')")
 */
class PlanningEventController extends AbstractController
{
    private $em;
    private $serializer;

    /** @var PlanningEventRepository */
    private $planningEventRepository;

    public function __construct(ManagerRegistry $doctrine, SerializerInterface $serializer)
    {
        $this->em = $doctrine->getManager();
        $this->serializer = $serializer;
        $this->planningEventRepository = $this->em->getRepository(PlanningEvent::class);
    }

    /**
     * @Route("", name="index")
     */
    public function index(Request $request): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $staff = $user->getStaff();

        if ($staff === null) {
            throw new NotFoundHttpException('Current logged user is not a staff');
        }

        if ($staff->getCampus() === null) {
            throw new BadRequestHttpException('Staff is not part of any campus');
        }

        $planningEvents = $this->planningEventRepository->findByCampusAndLevel(
            $staff->getCampus(),
            null,
            new \DateTime($request->query->get('start')),
            new \DateTime($request->query->get('end'))
        );

        return JsonResponse::fromJsonString($this->serializer->serialize($planningEvents, 'json'));
    }
}
