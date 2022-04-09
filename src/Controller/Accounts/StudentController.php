<?php

namespace App\Controller\Accounts;

use App\Entity\Student;
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

    public function __construct(ParameterBagInterface $params, LoggerInterface $logger, ManagerRegistry $doctrine)
    {
        $this->studentDocumentsDirectories = $params->get('app.accounts.student_documents_directories');
        $this->logger = $logger;
        $this->em = $doctrine->getManager();
        $this->studentRepository = $this->em->getRepository(Student::class);
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
    public function show(Student $student): Response
    {
        return $this->render('accounts/student/show.html.twig', [
            'student' => $student,
        ]);
    }

    /**
     * @Route("/{id}/documents", name="document")
     */
    public function getStudentDocuments(Student $student): Response
    {
        $filesystem = new Filesystem();
        $finder = new Finder();

        $studentDocumentsDirectory = $this->studentDocumentsDirectories . $student->getId();
        if (!$filesystem->exists($studentDocumentsDirectory)) {
            $filesystem->mkdir($studentDocumentsDirectory);

            $this->logger->debug('Directory ' . $studentDocumentsDirectory . 'created');
        }

        $studentDocuments = $finder->files()->in($studentDocumentsDirectory)->sortByName(true);
        return $this->render('accounts/student/documents.html.twig', [
            'student' => $student,
            'documents' => iterator_to_array($studentDocuments),
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
