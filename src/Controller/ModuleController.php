<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Module;
use App\Repository\ModuleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/modules", name="app.module.")
 * @Security("is_granted('ROLE_USER')")
 */
class ModuleController extends AbstractController
{
    private $em;
    private $moduleRepository;
    private $gradeRepository;

    public function __construct(ManagerRegistry $doctrine, ModuleRepository $moduleRepository)
    {
        $this->em = $doctrine->getManager();
        $this->moduleRepository = $moduleRepository;
        $this->gradeRepository = $this->em->getRepository(Grade::class);
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $student = $user->getStudent();

        $modules = $this->moduleRepository->findAll();
        $grades = $this->gradeRepository->findBy(['student' => $student->getId()]);

        // Set module label as grade key
        foreach ($grades as $key => $grade) {
            $grades[$grade->getModule()->getLabel()] = $grade;
            unset($grades[$key]);
        }

        return $this->render('module/index.html.twig', [
            'modules' => $modules,
            'grades' => $grades,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Module $module): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $student = $user->getStudent();

        if ($student !== null) {
            $gradeRepository = $this->em->getRepository(Grade::class);
            $grade = $gradeRepository->findOneBy(['module' => $module->getId(), 'student' => $student->getId()]);
        }

        return $this->render('module/show.html.twig', [
            'module' => $module,
            'grade' => isset($grade) ? $grade : null,
        ]);
    }
}
