<?php

namespace App\Service;

use App\Entity\DataSource;
use App\Entity\Role;
use App\Enum\RelationEnum;
use App\Enum\TypeEnum;
use App\Repository\DataSourceRepository;
use Doctrine\Common\Annotations\Reader as AnnotationsReader;
use Doctrine\Persistence\ManagerRegistry;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class DataLoaderService
{
    private $etlDataDirectory;
    private $logger;
    private $em;
    private $annotationsReader;

    /**
     * @var DataSourceRepository
     */
    private $dataSourceRepository;

    private const RELATIONS = [RelationEnum::ONE_TO_ONE, RelationEnum::ONE_TO_MANY, RelationEnum::MANY_TO_ONE, RelationEnum::MANY_TO_MANY];
    

    public function __construct(ParameterBagInterface $params, LoggerInterface $logger, ManagerRegistry $doctrine, AnnotationsReader $annotationsReader)
    {
        $this->etlDataDirectory = $params->get('app.etl_data_directory');
        $this->logger = $logger;
        $this->em = $doctrine->getManager();
        $this->dataSourceRepository = $this->em->getRepository(DataSource::class);
        $this->annotationsReader = $annotationsReader;
    }

    public function loadFiles()
    {
        $filesystem = new Filesystem();
        $finder = new Finder();

        $this->logger->debug('src\Service\DataLoaderService.php::loadFiles - Checking directory' . $this->etlDataDirectory);
        if (!$filesystem->exists($this->etlDataDirectory)) {
            throw new \Exception('Directory not found');
        }

        $finder->files()->in($this->etlDataDirectory)->notName('*.skip')->sortByName(true);
        if (!$finder->hasResults()) {
            throw new \Exception('No file found');
        }

        foreach ($finder as $file) {
            $splittedFilename = explode('~', $file->getFilename(), 2); // Character before "~" is only designed for priority
            $usedFilename = end($splittedFilename); // Filename is always at the last position (0 if no "~" character, 1 otherwise)

            $dataSource = $this->dataSourceRepository->findOneBy(['label' => $usedFilename]);
            if ($dataSource === null) {
                throw new \Exception('File "' . $file->getFilename() . '" has no associated dataSource (labeled "' . $usedFilename . '")');
            }

            if ($file->getExtension() === 'csv') {
                $this->loadCsv($dataSource, $file);
            }
        }
    }

    private function loadCsv(DataSource $dataSource, SplFileInfo $file)
    {
        $this->logger->debug('src\Service\DataLoaderService.php::loadCsv - Loading CSV', [ 'fileName' => $file->getFilename() ]);

        $csv = Reader::createFromPath($file->getPathname(), 'r');
        $csv->setHeaderOffset(0);
        $csv->setDelimiter(';');

        $records = $csv->getRecords();
        foreach ($records as $record) {
            $this->processRecord($dataSource, $record);
        }
    }

    private function processRecord(DataSource $dataSource, array $record) 
    {
        $this->logger->debug('src\Service\DataLoaderService.php::processRecord - Processing record', ['record' => $record]);

        $dataEquivalence = $dataSource->getEquivalence();
    
        $mainEntityClass = $dataEquivalence['main_entity']['entity'];

        // Replace empty strings by null
        $record = array_map(fn($v) => $v === '' ? null : $v, $record); 
        $this->logger->debug('src\Service\DataLoaderService.php::processRecord - Next value', ['value' => $record]);

        // Find existing entity
        $mainEntity = $this->findExistingMainEntity(
            $dataEquivalence['main_entity']['entity'], 
            $dataEquivalence['main_entity']['identified_by'], 
            $record
        );

        $fieldIsCleared = [];
        foreach ($dataEquivalence['fields'] as $field) {
            $this->logger->debug('src\Service\DataLoaderService.php::processRecord - Next field', ['field' => $field]);

            if ($field['metatype']['type'] === TypeEnum::RELATION) {
                $this->logger->debug('src\Service\DataLoaderService.php::processRecord - Field "' . $field['destination'] . '" is a relation. Getting it from main entity "' . $mainEntityClass . '"...');

                // Determine relation type (OneToOne, OneToMany, ManyToOne, ManyToMany)
                $relationType = $this->getEntityRelationType($mainEntityClass, $field['destination']);

                $this->logger->debug('src\Service\DataLoaderService.php::processRecord - Relation type: ' . $relationType);

                if ($relationType === RelationEnum::ONE_TO_ONE || $relationType === RelationEnum::MANY_TO_ONE) {
                    $subEntity = $mainEntity->__get($field['destination']);

                    if ($subEntity === null) {
                        $this->logger->debug('src\Service\DataLoaderService.php::processRecord - Main entity "' . $mainEntityClass . '" hasn\'t "' . $field['destination'] . '" relation. Getting it from repository...');

                        if ($field['metatype']['relation']['identified_by'] !== null) {
                            $subEntity = $this->em->getRepository($field['metatype']['relation']['entity'])->findOneBy([
                                $field['metatype']['relation']['identified_by']['destination'] => $record[$field['metatype']['relation']['identified_by']['source']],
                            ]);
                        }

                        if ($record[$field['metatype']['relation']['source']] !== null) {
                            if ($subEntity === null) {
                                $this->logger->debug('src\Service\DataLoaderService.php::processRecord - Sub entity of ' . $field['metatype']['relation']['entity'] . ' not exists yet. Creating...');

                                $subEntity = new $field['metatype']['relation']['entity'];
                            }

                            $subEntity->__set($field['metatype']['relation']['destination'], $this->getTypedValue($record[$field['metatype']['relation']['source']], $field['metatype']));

                            $this->em->persist($subEntity);
                        }

                        $mainEntity->__set($field['destination'], $subEntity);



                    } else {
                        $this->logger->debug('src\Service\DataLoaderService.php::processRecord - Setting value "' . $field['metatype']['relation']['destination'] . '" with "' . $record[$field['metatype']['relation']['source']] . '"');
                        
                        $subEntity->__set($field['metatype']['relation']['destination'], $this->getTypedValue($record[$field['metatype']['relation']['source']], $field['metatype']));
                    }


                } elseif ($relationType === RelationEnum::MANY_TO_MANY || $relationType === RelationEnum::ONE_TO_MANY) {
                    $subEntity = null;

                    if ($field['metatype']['relation']['identified_by'] !== null) {
                        $subEntity = $this->em->getRepository($field['metatype']['relation']['entity'])->findOneBy([
                            $field['metatype']['relation']['identified_by']['destination'] => $record[$field['metatype']['relation']['identified_by']['source']],
                        ]);
                    }

                    if ($subEntity === null) {
                        $this->logger->debug('src\Service\DataLoaderService.php::processRecord - Sub entity of ' . $field['metatype']['relation']['entity'] . ' not exists yet. Creating...');

                        $subEntity = new $field['metatype']['relation']['entity'];
                        $subEntity->__set($field['metatype']['relation']['destination'], $this->getTypedValue($record[$field['metatype']['relation']['source']], $field['metatype']));

                        $this->em->persist($subEntity);
                    }

                    if  (isset($field['options']['key_as_value'])) {
                        $subSubEntity = $this->em->getRepository($field['options']['key_as_value']['metatype']['entity'])->findOneBy([
                            $field['options']['key_as_value']['metatype']['identified_by'] => $field['options']['key_as_value']['metatype']['source']
                        ]);

                        if ($subSubEntity === null) {
                            $this->logger->debug('src\Service\DataLoaderService.php::processRecord - Sub sub entity of ' . $field['options']['key_as_value']['metatype']['entity'] . ' not exists yet. Creating...');

                            $subSubEntity = new $field['options']['key_as_value']['metatype']['entity'];
                            $subSubEntity->__set($field['options']['key_as_value']['metatype']['destination'], $this->getTypedValue($field['options']['key_as_value']['metatype']['source'], $field['options']['key_as_value']['metatype']));

                            $this->em->persist($subSubEntity);
                        }

                        $subEntity->__set($field['options']['key_as_value']['destination'], $subSubEntity);

                        $this->em->persist($subEntity);
                    }

                    if (!isset($fieldIsCleared[$field['destination']]) || $fieldIsCleared[$field['destination']] === false) {
                        $this->logger->debug('src\Service\DataLoaderService.php::processRecord - First time we see "' . $field['destination'] . '". Clearing...');

                        $mainEntity->__get($field['destination'])->clear();
                        $fieldIsCleared[$field['destination']] = true;
                    }

                    $mainEntity->__add($field['destination'], $subEntity);
                }

            } else {
                $this->logger->debug('src\Service\DataLoaderService.php::processRecord - Field "' . $field['destination'] . '" is not a relation.');

                $mainEntity->__set($field['destination'], $this->getTypedValue($record[$field['source']], $field['metatype']));
            }


            $this->em->persist($mainEntity);
            $this->em->flush();

            $this->logger->debug(' ');
        }
    }

    private function getTypedValue($value, array $metatype) {
        if (is_null($value) || is_object($value)) {
            return $value;
        }

        switch ($metatype['type']) {
            case TypeEnum::RELATION:
                return $this->getTypedValue($value, $metatype['relation']);

            case TypeEnum::STRING:
                return (string) $value;

            case TypeEnum::INT:
                return (int) $value;

            case TypeEnum::FLOAT:
                return (float) $value;

            case TypeEnum::BOOL:
                return filter_var($value, FILTER_VALIDATE_BOOL);

            case TypeEnum::DATE:
                if (!isset($metatype['date_format'])) {
                    throw new \Exception('Missing date format');
                }
                return \DateTime::createFromFormat($metatype['date_format'], $value);
            
            case TypeEnum::ROLE:
                $role = $this->em->getRepository(Role::class)->findOneBy(['from' => $value]);
                return $role === null ? '' : $role->getTo();

            default:
                throw new \Exception('Unknown type "' . $metatype['type'] . '"');
        }
    }

    private function findExistingMainEntity(string $className, array $identifierField, array $value)
    {
        // Entity identified by another entity
        if ($identifierField['metatype']['type'] === TypeEnum::RELATION) {

            $subEntity = $this->findExistingEntity(
                $identifierField['metatype']['relation']['entity'], 
                $identifierField['metatype'],
                $identifierField['metatype']['relation']['destination'], 
                $value[$identifierField['metatype']['relation']['source']], 
            );

            $entity = $this->findExistingEntity(
                $className, 
                $identifierField['metatype'],
                $identifierField['destination'], 
                $subEntity, 
            );
        } else {

            $entity = $this->findExistingEntity(
                $className, 
                $identifierField['metatype'],
                $identifierField['destination'], 
                $value[$identifierField['source']], 
            );
        }

        return $entity;
    }

    private function findExistingEntity(string $className, array $metatype, string $field, $value)
    {
        $repository = $this->em->getRepository($className);

        $entity = $repository->findOneBy([$field => $value]);

        if ($entity === null) {
            $entity = new $className;
            $entity->__set($field, $this->getTypedValue($value, $metatype));

            $this->em->persist($entity);
            $this->em->flush();
        }

        return $entity;
    }

    private function getEntityRelationType(string $className, string $property)
    {
        $entityProperty = (new \ReflectionClass($className))->getProperty($property);
        $annotations = array_map('get_class', $this->annotationsReader->getPropertyAnnotations($entityProperty)); // Stringify all class name
        $relationType = array_values(array_intersect(self::RELATIONS, $annotations))[0];

        return $relationType;
    }
}
