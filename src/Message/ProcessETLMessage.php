<?php

namespace App\Message;

final class ProcessETLMessage
{
    private $importId;

    public function __construct(string $importId)
    {
        $this->importId = $importId;
    }

   public function getImportId(): string
   {
       return $this->importId;
   }
}
