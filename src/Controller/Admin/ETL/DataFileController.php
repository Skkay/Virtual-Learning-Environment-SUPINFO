<?php

namespace App\Controller\Admin\ETL;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/etl/data_files", name="app.admin.etl.data_file.")
 */
class DataFileController extends AbstractController
{
    private $etlDataDirectory;

    public function __construct(ParameterBagInterface $params)
    {
        $this->etlDataDirectory = $params->get('app.etl_data_directory');
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $finder = new Finder();

        $finder->files()->in($this->etlDataDirectory)->exclude('.old')->notName('*.skip')->sortByName(true);

        return $this->render('admin/etl/data_file/index.html.twig', [
            'files' => iterator_to_array($finder),
        ]);
    }
}
