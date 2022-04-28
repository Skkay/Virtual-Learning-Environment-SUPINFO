<?php

namespace App\Controller\EducationalCoordinator;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/educational_coordinator/companies", name="app.educational_coordinator.company.")
 * @Security("is_granted('ROLE_EDUCATIONAL_COORDINATOR')")
 */
class CompanyController extends AbstractController
{
    private $em;

    /** @var CompanyRepository */
    private $companyRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->companyRepository = $this->em->getRepository(Company::class);
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $companies = $this->companyRepository->findAll();

        return $this->render('educational_coordinator/company/index.html.twig', [
            'companies' => $companies,
        ]);
    }
}
