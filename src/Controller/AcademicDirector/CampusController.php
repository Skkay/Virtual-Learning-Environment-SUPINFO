<?php

namespace App\Controller\AcademicDirector;

use App\Entity\Campus;
use App\Entity\Level;
use App\Entity\Student;
use App\Repository\CampusRepository;
use App\Repository\LevelRepository;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/academic_director/campus", name="app.academic_director.campus.")
 * @Security("is_granted('ROLE_ACADEMIC_DIRECTOR')")
 */
class CampusController extends AbstractController
{
    private $em;

    /** @var CampusRepository */
    private $campusRepository;

    /** @var StudentRepository */
    private $studentRepository;

    /** @var LevelRepository */
    private $levelRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->campusRepository = $this->em->getRepository(Campus::class);
        $this->studentRepository = $this->em->getRepository(Student::class);
        $this->levelRepository = $this->em->getRepository(Level::class);
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $campus = $this->campusRepository->findAll();

        $nbActiveStudents = $this->studentRepository->countActiveStudentsInCampus();
        foreach ($nbActiveStudents as $key => $value) {
            $nbActiveStudents[$value['campus_label']] = $value['count'];
            unset($nbActiveStudents[$key]);
        }

        return $this->render('academic_director/campus/index.html.twig', [
            'campus' => $campus,
            'nbActiveStudents' => $nbActiveStudents,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Campus $campus)
    {
        $levels = $this->levelRepository->findBy([], ['label' => 'ASC']);

        $nbStudents = $this->studentRepository->countStudentsByCampus($campus);
        foreach ($nbStudents as $key => $value) {
            $nbStudents[$value['level_label']] = $value['count'];
            unset($nbStudents[$key]);
        }

        return $this->render('academic_director/campus/show.html.twig', [
            'campus' => $campus,
            'levels' => $levels,
            'nbStudents' => $nbStudents,
        ]);
    }
}
