<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/companies", name="app.admin.company.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class CompanyController extends AbstractController
{
    private $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $companies = $this->companyRepository->findAll();

        return $this->render('admin/company/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Company $company): Response
    {
        return $this->render('admin/company/show.html.twig', [
            'company' => $company,
        ]);
    }
}
