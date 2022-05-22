<?php

namespace App\Controller\AcademicDirector;

use App\Entity\Instructor;
use App\Repository\InstructorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/academic_director/instructors", name="app.academic_director.instructor.")
 * @Security("is_granted('ROLE_ACADEMIC_DIRECTOR')")
 */
class InstructorController extends AbstractController
{
    private $em;

    /** @var InstructorRepository */
    private $instructorRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->instructorRepository = $this->em->getRepository(Instructor::class);
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $staff = $user->getStaff();

        if ($staff === null) {
            throw new NotFoundHttpException('Current logged user is not a staff');
        }

        $instructors = $this->instructorRepository->findAll();

        return $this->render('academic_director/instructor/index.html.twig', [
            'instructors' => $instructors,
        ]);
    }
}
