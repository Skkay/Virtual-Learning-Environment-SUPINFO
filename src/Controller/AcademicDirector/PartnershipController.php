<?php

namespace App\Controller\AcademicDirector;

use App\Entity\Partnership;
use App\Repository\PartnershipRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/academic_director/partnership", name="app.academic_director.partnership.")
 * @Security("is_granted('ROLE_ACADEMIC_DIRECTOR')")
 */
class PartnershipController extends AbstractController
{
    private $em;

    /** @var PartnershipRepository */
    private $partnershipRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->partnershipRepository = $this->em->getRepository(Partnership::class);
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $partnerships = $this->partnershipRepository->findAll();

        return $this->render('academic_director/partnership/index.html.twig', [
            'partnerships' => $partnerships,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Partnership $partnership): Response
    {
        return $this->render('academic_director/partnership/show.html.twig', [
            'partnership' => $partnership,
        ]);
    }
}
