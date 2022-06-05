<?php

namespace App\Command;

use App\Service\ExportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DatabaseExportCommand extends Command
{
    protected static $defaultName = 'app:database-export';
    protected static $defaultDescription = 'Export database';

    private $exportDir;
    private $exportService;

    public function __construct(ParameterBagInterface $params, ExportService $exportService)
    {
        $this->exportDir = $params->get('app.database.export_directory');
        $this->exportService = $exportService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('format', InputArgument::REQUIRED, 'Output format (CSV, SQL)')
            ->addOption('path', 'p', InputOption::VALUE_REQUIRED, 'Output path', $this->exportDir)
            ->addOption('name', 'N', InputOption::VALUE_REQUIRED, 'Output name')
            ->addOption('prefix', null, InputOption::VALUE_REQUIRED, 'Prefix name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $format = $input->getArgument('format');
        $path = $input->getOption('path');
        $name = $input->getOption('name');
        $prefix = $input->getOption('prefix');

        if (strtolower($format) === 'sql') {
            $io->note('Exporting database to SQL...');

            $outputFile = $this->exportService->exportToSQL($path, $name, $prefix);

            $io->success('Successfully exported to ' . $outputFile);
        }

        return Command::SUCCESS;
    }
}
