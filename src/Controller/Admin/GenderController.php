<?php

namespace App\Controller\Admin;

use App\Entity\Gender;
use App\Repository\GenderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/genders", name="app.admin.gender.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class GenderController extends AbstractController
{
    private $genderRepository;

    public function __construct(GenderRepository $genderRepository)
    {
        $this->genderRepository = $genderRepository;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $genders = $this->genderRepository->findAll();

        return $this->render('admin/gender/index.html.twig', [
            'genders' => $genders,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Gender $gender): Response
    {
        return $this->render('admin/gender/show.html.twig', [
            'gender' => $gender,
        ]);
    }
}
