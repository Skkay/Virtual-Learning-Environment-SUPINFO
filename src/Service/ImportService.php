<?php

namespace App\Service;

use App\Entity\Import;

class ImportService
{
    public function getProgressions(Import $import): array
    {
        $percentFile = ($import->getCurrentFile() / $import->getNbFiles()) * 100;
        $percentLine = ($import->getCurrentLine() / $import->getNbLines()) * 100;

        $chunk = 100 / $import->getNbFiles();
        $overall = ($import->getCurrentLine() * $chunk / $import->getNbLines()) + (($import->getCurrentFile() - 1) * $chunk);

        return [
            'files' => $percentFile,
            'lines' => $percentLine,
            'overall' => $overall,
        ];
    }
}
