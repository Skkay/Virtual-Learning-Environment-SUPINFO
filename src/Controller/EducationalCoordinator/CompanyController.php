<?php

namespace App\Controller\EducationalCoordinator;

use App\Entity\Company;
use App\Entity\Student;
use App\Repository\CompanyRepository;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    /** @var StudentRepository */
    private $studentRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->companyRepository = $this->em->getRepository(Company::class);
        $this->studentRepository = $this->em->getRepository(Student::class);
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $companies = $this->companyRepository->findAll();

        $nbStudentsInTrainingContract = $this->studentRepository->findNbOfStudentsInTrainingContract();
        foreach ($nbStudentsInTrainingContract as $key => $value) {
            $nbStudentsInTrainingContract[$value['company_name']] = $value['count'];
            unset($nbStudentsInTrainingContract[$key]);
        }

        $nbStudentsHired = $this->studentRepository->findNbOfStudentsHired();
        foreach ($nbStudentsHired as $key => $value) {
            $nbStudentsHired[$value['company_name']] = $value['count'];
            unset($nbStudentsHired[$key]);
        }

        return $this->render('educational_coordinator/company/index.html.twig', [
            'companies' => $companies,
            'nbStudentsInTrainingContract' => $nbStudentsInTrainingContract,
            'nbStudentsHired' => $nbStudentsHired,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Company $company): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $staff = $user->getStaff();

        if ($staff === null) {
            throw new NotFoundHttpException('Current logged user is not a staff');
        }

        $studentsInTrainingContract = $this->studentRepository->findByCampusAndCompany($staff->getCampus(), $company, false);
        $studentsHired = $this->studentRepository->findByCampusAndCompany($staff->getCampus(), $company, true);

        return $this->render('educational_coordinator/company/show.html.twig', [
            'company' => $company,
            'studentsInTrainingContract' => $studentsInTrainingContract,
            'studentsHired' => $studentsHired,
        ]);
    }
}
