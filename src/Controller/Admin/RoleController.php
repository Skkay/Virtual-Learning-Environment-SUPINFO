<?php

namespace App\Controller\Admin;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/roles", name="app.admin.role.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class RoleController extends AbstractController
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $roles = $this->roleRepository->findAll();

        return $this->render('admin/role/index.html.twig', [
            'roles' => $roles,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Role $role): Response
    {
        return $this->render('admin/role/show.html.twig', [
            'role' => $role,
        ]);
    }
}
