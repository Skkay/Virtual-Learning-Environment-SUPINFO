<?php

namespace App\Controller;

use App\Repository\StaffRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/staffs", name="app.staff.")
 */
class StaffController extends AbstractController
{
    private $staffRepository;

    public function __construct(StaffRepository $staffRepository)
    {
        $this->staffRepository = $staffRepository;
    }
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $staffs = $this->staffRepository->findAll();

        return $this->render('staff/index.html.twig', [
            'staffs' => $staffs,
        ]);
    }
}
