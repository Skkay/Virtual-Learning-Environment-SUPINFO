<?php

namespace App\Controller\Student;

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
 * @Route("/planning_events", name="app.planning_events.")
 * @Security("is_granted('ROLE_USER')")
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
        $student = $user->getStudent();

        if ($student === null) {
            throw new NotFoundHttpException('Current logged user is not a student');
        }

        if ($student->getCampus() === null) {
            throw new BadRequestHttpException('Student is not part of any campus');
        }

        if ($student->getLevel() === null) {
            throw new BadRequestHttpException('Student has not level');
        }

        $planningEvents = $this->planningEventRepository->findByCampusAndLevel(
            $student->getCampus(),
            $student->getLevel(),
            new \DateTime($request->query->get('start')),
            new \DateTime($request->query->get('end'))
        );

        return JsonResponse::fromJsonString($this->serializer->serialize($planningEvents, 'json'));
    }
}
