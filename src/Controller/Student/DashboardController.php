<?php

namespace App\Controller\Student;

use App\Entity\Level;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard", name="app.dashboard.")
 * @Security("is_granted('ROLE_USER')")
 */
class DashboardController extends AbstractController
{
    private $em;
    private $levelRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->levelRepository = $this->em->getRepository(Level::class);
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $student = $user->getStudent();

        if ($student === null) {
            throw new NotFoundHttpException('Current logged user is not a student');
        }

        $levels = $this->levelRepository->findAll();

        return $this->render('student/dashboard/index.html.twig', [
            'student' => $student,
            'levels' => $levels,
        ]);
    }
}
