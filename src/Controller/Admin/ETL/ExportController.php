<?php

namespace App\Controller\Admin\ETL;

use App\Service\ExportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/etl/exports", name="app.admin.etl.export.")
 */
class ExportController extends AbstractController
{
    private $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $exports = $this->exportService->getExportsFiles();

        return $this->render('admin/etl/export/index.html.twig', [
            'exports' => $exports,
        ]);
    }
}
