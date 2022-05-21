<?php

namespace App\Controller\Admin;

use App\Entity\AccountsPaymentType;
use App\Repository\AccountsPaymentTypeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/accounts_payment_type", name="app.admin.accountsPaymentType.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AccountsPaymentTypeController extends AbstractController
{
    private $accountsPaymentTypeRepository;

    public function __construct(AccountsPaymentTypeRepository $accountsPaymentTypeRepository)
    {
        $this->accountsPaymentTypeRepository = $accountsPaymentTypeRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $accountsPaymentTypes = $this->accountsPaymentTypeRepository->findAll();

        return $this->render('admin/accounts_payment_type/index.html.twig', [
            'accountsPaymentTypes' => $accountsPaymentTypes,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(AccountsPaymentType $accountsPaymentType): Response
    {
        return $this->render('admin/accounts_payment_type/show.html.twig', [
            'accountsPaymentType' => $accountsPaymentType,
        ]);
    }
}
