<?php

namespace App\Controller\Accounts;

use App\Entity\AccountsStudentComment;
use App\Entity\Student;
use App\Form\DocumentUploadType;
use App\Form\StudentCommentType;
use App\Repository\AccountsStudentCommentRepository;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/accounts/students", name="app.accounts.student.")
 * @Security("is_granted('ROLE_ADMINISTRATION')")
 */
class StudentController extends AbstractController
{
    private $studentDocumentsDirectories;
    private $logger;
    private $em;

    /** @var StudentRepository */
    private $studentRepository;

    /** @var AccountsStudentCommentRepository */
    private $accountsStudentCommentRepository;

    public function __construct(ParameterBagInterface $params, LoggerInterface $logger, ManagerRegistry $doctrine)
    {
        $this->studentDocumentsDirectories = $params->get('app.accounts.student_documents_directories');
        $this->logger = $logger;
        $this->em = $doctrine->getManager();
        $this->studentRepository = $this->em->getRepository(Student::class);
        $this->accountsStudentCommentRepository = $this->em->getRepository(AccountsStudentComment::class);
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $students = $this->studentRepository->findAll();

        return $this->render('accounts/student/index.html.twig', [
            'students' => $students,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Student $student, Request $request): Response
    {
        $commentForm = $this->createForm(StudentCommentType::class);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment = $commentForm->get('comment')->getData();

            $student->addAccountsComment(new AccountsStudentComment($comment));
            $this->em->persist($student);
            $this->em->flush();

            return $this->redirectToRoute('app.accounts.student.show', [
                'id' => $student->getId(),
            ]);
        }

        $comments = $this->accountsStudentCommentRepository->findBy(['student' => $student->getId()], ['createdAt' => 'DESC']);

        return $this->render('accounts/student/show.html.twig', [
            'student' => $student,
            'commentForm' => $commentForm->createView(),
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/{id}/documents", name="document")
     */
    public function getStudentDocuments(Student $student, Request $request): Response
    {
        $filesystem = new Filesystem();
        $finder = new Finder();

        $studentDocumentsDirectory = $this->studentDocumentsDirectories . $student->getId();
        if (!$filesystem->exists($studentDocumentsDirectory)) {
            $filesystem->mkdir($studentDocumentsDirectory);

            $this->logger->debug('Directory ' . $studentDocumentsDirectory . 'created');
        }

        $documentUploadForm = $this->createForm(DocumentUploadType::class);
        $documentUploadForm->handleRequest($request);

        if ($documentUploadForm->isSubmitted() && $documentUploadForm->isValid()) {
            $files = $documentUploadForm->get('file')->getData();

            foreach ($files as $file) {
                $file->move($studentDocumentsDirectory, $file->getClientOriginalName());
            }
        }

        $studentDocuments = $finder->files()->in($studentDocumentsDirectory)->sortByName(true);

        return $this->render('accounts/student/documents.html.twig', [
            'student' => $student,
            'documents' => iterator_to_array($studentDocuments),
            'documentUploadForm' => $documentUploadForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/documents/{filename}", name="open_document")
     */
    public function openDocument(Student $student, string $filename, Request $request): Response
    {
        $finder = new Finder();

        $documentsDirectory = $this->studentDocumentsDirectories . $student->getId() . '/';
        $documentFinder = $finder->files()->in($documentsDirectory)->name($filename);

        if (!$documentFinder->hasResults()) {
            throw new NotFoundHttpException('Requested file "' . $documentsDirectory . $filename . '" not found');
        }

        $documentPath = iterator_to_array($documentFinder, false)[0]->getRealPath();

        if ($request->query->has('download')) {
            return $this->file($documentPath);
        }

        return new BinaryFileResponse($documentPath);
    }
}
