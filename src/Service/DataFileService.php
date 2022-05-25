<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DataFileService
{
    private $etlDataDirectory;
    private $logger;

    public function __construct(ParameterBagInterface $params, LoggerInterface $logger)
    {
        $this->etlDataDirectory = $params->get('app.etl_data_directory');
        $this->logger = $logger;
    }

    public function getDataFiles(): array
    {
        $finder = new Finder();

        $finder->files()->in($this->etlDataDirectory)->exclude('.old')->notName('*.skip')->sortByName(true);

        return iterator_to_array($finder);
    }
}
