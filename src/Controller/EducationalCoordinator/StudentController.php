<?php

namespace App\Controller\EducationalCoordinator;

use App\Entity\Student;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/educational_coordinator/students", name="app.educational_coordinator.student.")
 * @Security("is_granted('ROLE_EDUCATIONAL_COORDINATOR')")
 */
class StudentController extends AbstractController
{
    private $em;

    /** @var StudentRepository */
    private $studentRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->studentRepository = $this->em->getRepository(Student::class);
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $students = $this->studentRepository->findAll();

        return $this->render('educational_coordinator/student/index.html.twig', [
            'students' => $students,
        ]);
    }
}
