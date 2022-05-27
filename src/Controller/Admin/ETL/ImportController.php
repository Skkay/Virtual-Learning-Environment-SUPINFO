<?php

namespace App\Controller\Admin\ETL;

use App\Repository\ImportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/etl/imports", name="app.admin.etl.import.")
 */
class ImportController extends AbstractController
{
    /** @var ImportRepository */
    private $importRepository;

    public function __construct(ImportRepository $importRepository)
    {
        $this->importRepository = $importRepository;
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
}
