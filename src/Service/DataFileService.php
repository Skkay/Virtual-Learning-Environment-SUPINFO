<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
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

    public function clearDataFileDirectory()
    {
        $filesystem = new Filesystem();
        $finder = new Finder();

        $destDirName = time() . '/';
        
        $finder->files()->in($this->etlDataDirectory)->exclude('.old');
        foreach ($finder as $file) {
            $this->logger->debug(sprintf('Copying file "%s" into "%s"', $this->etlDataDirectory . $file->getRelativePathname(), $this->etlDataDirectory . '.old/' . $destDirName . $file->getRelativePathname()));
            $filesystem->copy($this->etlDataDirectory . $file->getRelativePathname(), $this->etlDataDirectory . '.old/' . $destDirName . $file->getRelativePathname());
        }
        
        $finder->files()->in($this->etlDataDirectory)->exclude('.old');
        foreach ($finder as $file) {
            $this->logger->debug(sprintf('Removing file "%s"', $this->etlDataDirectory . $file->getRelativePathname()));
            $filesystem->remove($this->etlDataDirectory . $file->getRelativePathname());
        }
        
        $finder->directories()->in($this->etlDataDirectory)->exclude('.old')->depth('== 0');
        foreach ($finder as $dir) {
            $this->logger->debug(sprintf('Removing directory "%s"', $this->etlDataDirectory . $dir->getRelativePathname()));
            $filesystem->remove($this->etlDataDirectory . $dir->getRelativePathname());
        }
    }

    public function handleUploadedFiles(array $files)
    {
        foreach ($files as $file) {
            $file->move($this->etlDataDirectory, $file->getClientOriginalName());
        }
    }
}
