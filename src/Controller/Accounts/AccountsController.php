<?php

namespace App\Controller\Accounts;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/accounts", name="app.accounts.")
 * @Security("is_granted('ROLE_ACCOUNTS')")
 */
class AccountsController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        return $this->render('accounts/index.html.twig');
    }
}
