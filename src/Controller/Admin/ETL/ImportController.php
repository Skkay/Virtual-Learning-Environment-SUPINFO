<?php

namespace App\Controller\Admin\ETL;

use App\Entity\Import;
use App\Message\ProcessETLMessage;
use App\Repository\ImportRepository;
use App\Service\ImportService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/etl/imports", name="app.admin.etl.import.")
 */
class ImportController extends AbstractController
{
    private $em;
    private $importRepository;
    private $importService;
    private $bus;

    public function __construct(ManagerRegistry $doctrine, ImportRepository $importRepository, ImportService $importService, MessageBusInterface $bus)
    {
        $this->em = $doctrine->getManager();
        $this->importRepository = $importRepository;
        $this->importService = $importService;
        $this->bus = $bus;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $imports = $this->importRepository->findAll();

        return $this->render('admin/etl/import/index.html.twig', [
            'imports' => $imports,
        ]);
    }

    /**
     * @Route("/start", name="start")
     */
    public function start()
    {
        $import = new Import();
        $this->em->persist($import);
        $this->em->flush();

        $this->bus->dispatch(new ProcessETLMessage($import->getId()));

        return $this->redirectToRoute('app.admin.etl.import.show', [
            'id' => $import->getId(),
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Import $import): Response
    {
        return $this->render('admin/etl/import/show.html.twig', [
            'import' => $import,
            'progress' => $this->importService->getProgressions($import),
            'update_progress_url' => $this->generateUrl('app.admin.etl.import.update_progress', ['id' => $import->getId()]),
        ]);
    }

    /**
     * @Route("/{id}/update_progress", name="update_progress")
     */
    public function updateProgression(Import $import): Response
    {
        return $this->json([
            'import' => $import,
            'progress' => $this->importService->getProgressions($import)
        ]);
    }
}
