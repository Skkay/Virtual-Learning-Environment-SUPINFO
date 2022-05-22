<?php

namespace App\Controller\Admin;

use App\Entity\Speciality;
use App\Repository\SpecialityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/specialities", name="app.admin.speciality.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class SpecialityController extends AbstractController
{
    private $specialityRepository;

    public function __construct(SpecialityRepository $specialityRepository)
    {
        $this->specialityRepository = $specialityRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $specialities = $this->specialityRepository->findAll();

        return $this->render('admin/speciality/index.html.twig', [
            'specialities' => $specialities,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Speciality $speciality): Response
    {
        return $this->render('admin/speciality/show.html.twig', [
            'speciality' => $speciality,
        ]);
    }
}
