<?php

namespace App\Controller\Admin\ETL;

use App\Service\ExportService;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/admin/etl/exports", name="app.admin.etl.export.")
 */
class ExportController extends AbstractController
{
    private $exportService;
    private $exportDirectory;

    public function __construct(ExportService $exportService, ParameterBagInterface $params)
    {
        $this->exportService = $exportService;
        $this->exportDirectory = $params->get('app.database.export_directory');
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

    /**
     * @Route("/{filename}", name="download_file")
     */
    public function downloadFile(string $filename): Response
    {
        $finder = new Finder();

        $finder->files()->in($this->exportDirectory)->name($filename);

        if (!$finder->hasResults()) {
            throw new NotFoundHttpException('Requested file "' . $this->exportDirectory . '/' . $filename . '" not fond');
        }

        $filePath = iterator_to_array($finder, false)[0]->getRealPath();

        return $this->file($filePath);
    }
}
