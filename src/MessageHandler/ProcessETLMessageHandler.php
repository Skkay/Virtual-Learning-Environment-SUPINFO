<?php

namespace App\MessageHandler;

use App\Message\ProcessETLMessage;
use App\Repository\ImportRepository;
use App\Service\DataLoaderService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ProcessETLMessageHandler implements MessageHandlerInterface
{
    private $dataLoaderService;
    private $importRepository;

    public function __construct(DataLoaderService $dataLoaderService, ImportRepository $importRepository)
    {
        $this->dataLoaderService = $dataLoaderService;
        $this->importRepository = $importRepository;
    }

    public function __invoke(ProcessETLMessage $message)
    {
        $import = $this->importRepository->find($message->getImportId());

        $this->dataLoaderService->start($import);
    }
}
