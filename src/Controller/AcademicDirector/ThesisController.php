<?php

namespace App\Controller\AcademicDirector;

use App\Entity\Thesis;
use App\Repository\ThesisRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/academic_director/theses", name="app.academic_director.thesis.")
 * @Security("is_granted('ROLE_ACADEMIC_DIRECTOR')")
 */
class ThesisController extends AbstractController
{
    private $em;

    /** @var ThesisRepository */
    private $thesisRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->thesisRepository = $this->em->getRepository(Thesis::class);
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $theses = $this->thesisRepository->findAll();

        return $this->render('academic_director/thesis/index.html.twig', [
            'theses' => $theses,
        ]);
    }
}