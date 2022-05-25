<?php

namespace App\Controller\Admin\ETL;

use App\Form\DataFileUploadType;
use App\Service\DataFileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request): Response
    {
        $files = $this->dataFileService->getDataFiles();

        $dataFileUploadForm = $this->createForm(DataFileUploadType::class);
        $dataFileUploadForm->handleRequest($request);

        if ($dataFileUploadForm->isSubmitted() && $dataFileUploadForm->isValid()) {
            $uploadedFiles = $dataFileUploadForm->get('file')->getData();
            
            $this->dataFileService->handleUploadedFiles($uploadedFiles);

            return $this->redirectToRoute('app.admin.etl.data_file.index');
        }

        return $this->render('admin/etl/data_file/index.html.twig', [
            'files' => $files,
            'dataFileUploadForm' => $dataFileUploadForm->createView(),
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
