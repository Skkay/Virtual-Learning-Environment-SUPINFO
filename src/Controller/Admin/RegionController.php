<?php

namespace App\Controller\Admin;

use App\Entity\Region;
use App\Repository\RegionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/regions", name="app.admin.region.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class RegionController extends AbstractController
{
    private $regionRepository;

    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $regions = $this->regionRepository->findAll();

        return $this->render('admin/region/index.html.twig', [
            'regions' => $regions,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Region $region): Response
    {
        return $this->render('admin/region/show.html.twig', [
            'region' => $region,
        ]);
    }
}
