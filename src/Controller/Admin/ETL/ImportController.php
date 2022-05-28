<?php

namespace App\Controller\Admin\ETL;

use App\Entity\Import;
use App\Repository\ImportRepository;
use App\Service\ImportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/etl/imports", name="app.admin.etl.import.")
 */
class ImportController extends AbstractController
{
    private $importRepository;
    private $importService;

    public function __construct(ImportRepository $importRepository, ImportService $importService)
    {
        $this->importRepository = $importRepository;
        $this->importService = $importService;
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
