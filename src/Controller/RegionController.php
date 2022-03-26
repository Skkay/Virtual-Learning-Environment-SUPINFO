<?php

namespace App\Controller;

use App\Entity\Region;
use App\Repository\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/regions", name="app.region.")
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

        return $this->render('region/index.html.twig', [
            'regions' => $regions,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Region $region): Response
    {
        return $this->render('region/show.html.twig', [
            'region' => $region,
        ]);
    }
}
