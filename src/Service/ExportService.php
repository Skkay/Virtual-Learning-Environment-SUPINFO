<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

class ExportService
{
    private $exportDirectory;

    private $dbHost;
    private $dbPort;
    private $dbUser;
    private $dbPassword;
    private $dbName;

    public function __construct(ParameterBagInterface $params)
    {
        $this->exportDirectory = $params->get('app.database.export_directory');

        $this->dbHost = $params->get('app.database.host');
        $this->dbPort = $params->get('app.database.port');
        $this->dbUser = $params->get('app.database.user');
        $this->dbPassword = $params->get('app.database.password');
        $this->dbName = $params->get('app.database.name');
    }

    public function getExportsFiles(): array
    {
        $finder = new Finder();

        $finder->files()->in($this->exportDirectory)->sortByChangedTime()->reverseSorting();

        return iterator_to_array($finder);
    }

    /**
     * Export database to SQL format.
     *
     * @param string      $path The path to the output file.
     * @param string|null $name The name of the output file. If null, the name will be the current date and time in the format `Y-m-d_H-i-s`.
     * 
     * @throws \Exception If the process is not successful.
     * 
     * @return string Full path of the output file.
     */
    public function exportToSQL(string $path, string $name = null, $prefix = null): string
    {
        $filesystem = new Filesystem();

        $process = new Process([
            'mysqldump',
            '--host=' . $this->dbHost,
            '--port=' . $this->dbPort,
            '--user=' . $this->dbUser,
            '--password=' .  $this->dbPassword,
            $this->dbName,
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception($process->getErrorOutput());
        }

        $file = rtrim($path, '/') . '/' . ($prefix ?? '') . ($name ?? date('Y-m-d_H-i-s')) . '.sql';

        $filesystem->dumpFile($file, $process->getOutput());

        return $file;
    }
}
