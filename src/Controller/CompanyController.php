<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/companies", name="app.company.")
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

        return $this->render('company/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Company $company): Response
    {
        return $this->render('company/show.html.twig', [
            'company' => $company,
        ]);
    }
}
