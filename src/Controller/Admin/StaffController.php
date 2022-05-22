<?php

namespace App\Controller\Admin;

use App\Entity\Staff;
use App\Repository\StaffRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/staffs", name="app.admin.staff.")
 * @Security("is_granted('ROLE_ADMIN')")
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

        return $this->render('admin/staff/index.html.twig', [
            'staffs' => $staffs,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Staff $staff): Response
    {
        return $this->render('admin/staff/show.html.twig', [
            'staff' => $staff,
        ]);
    }
}
