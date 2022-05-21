<?php

namespace App\Controller\Admin;

use App\Entity\AccountsStudentComment;
use App\Repository\AccountsStudentCommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/accountsStudentComment", name="app.admin.accountsStudentComment.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AccountsStudentCommentController extends AbstractController
{
    private $accountsStudentCommentRepository;

    public function __construct(AccountsStudentCommentRepository $accountsStudentCommentRepository)
    {
        $this->accountsStudentCommentRepository = $accountsStudentCommentRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $accountsStudentComments = $this->accountsStudentCommentRepository->findAll();

        return $this->render('admin/accounts_student_comment/index.html.twig', [
            'accountsStudentComments' => $accountsStudentComments,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(AccountsStudentComment $accountsStudentComment): Response
    {
        return $this->render('admin/accounts_student_comment/show.html.twig', [
            'accountsStudentComment' => $accountsStudentComment,
        ]);
    }
}
