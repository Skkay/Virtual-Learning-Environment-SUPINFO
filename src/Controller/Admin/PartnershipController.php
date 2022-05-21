<?php

namespace App\Controller\Admin;

use App\Entity\Partnership;
use App\Repository\PartnershipRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/partnerships", name="app.admin.partnership.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class PartnershipController extends AbstractController
{
    private $partnershipRepository;

    public function __construct(PartnershipRepository $partnershipRepository)
    {
        $this->partnershipRepository = $partnershipRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $partnerships = $this->partnershipRepository->findAll();

        return $this->render('admin/partnership/index.html.twig', [
            'partnerships' => $partnerships,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Partnership $partnership): Response
    {
        return $this->render('admin/partnership/show.html.twig', [
            'partnership' => $partnership,
        ]);
    }
}
