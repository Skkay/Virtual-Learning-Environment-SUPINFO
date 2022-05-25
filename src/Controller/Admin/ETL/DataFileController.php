<?php

namespace App\Controller\Admin\ETL;

use App\Service\DataFileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/etl/data_files", name="app.admin.etl.data_file.")
 */
class DataFileController extends AbstractController
{
    private $dataFileService;

    public function __construct(DataFileService $dataFileService)
    {
        $this->dataFileService = $dataFileService;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $files = $this->dataFileService->getDataFiles();

        return $this->render('admin/etl/data_file/index.html.twig', [
            'files' => $files,
        ]);
    }

    /**
     * @Route("/clear", name="clear")
     */
    public function clear(): Response
    {
        $this->dataFileService->clearDataFileDirectory();

        return $this->redirectToRoute('app.admin.etl.data_file.index');
    }
}
