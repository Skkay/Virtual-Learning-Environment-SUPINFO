<?php

namespace App\Service;

use League\Csv\Reader;
use App\Entity\DataSource;
use Psr\Log\LoggerInterface;
use Symfony\Component\Finder\Finder;
use App\Repository\DataSourceRepository;
use Doctrine\Common\Annotations\Reader as AnnotationsReader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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

    private const ONE_TO_ONE   = 'Doctrine\\ORM\\Mapping\\OneToOne';
    private const ONE_TO_MANY  = 'Doctrine\\ORM\\Mapping\\OneToMany';
    private const MANY_TO_ONE  = 'Doctrine\\ORM\\Mapping\\ManyToOne';
    private const MANY_TO_MANY = 'Doctrine\\ORM\\Mapping\\ManyToMany';
    private const MAPPING_RELATIONS = [self::ONE_TO_ONE, self::ONE_TO_MANY, self::MANY_TO_ONE, self::MANY_TO_MANY];

    public function __construct(ParameterBagInterface $params, LoggerInterface $logger, ManagerRegistry $doctrine, AnnotationsReader $annotationsReader)
    {
        $this->etlDataDirectory = $params->get('app.etl_data_directory');
        $this->logger = $logger;
        $this->em = $doctrine->getManager();
        $this->dataSourceRepository = $this->em->getRepository(DataSource::class);
        $this->annotationsReader = $annotationsReader;
    }

    public function loadCsv() 
    {
        $filesystem = new Filesystem();
        $finder = new Finder();

        $this->logger->debug('src\Service\DataLoaderService.php::loadCsv - Checking directory' . $this->etlDataDirectory);
        if (!$filesystem->exists($this->etlDataDirectory)) {
            throw new \Exception('Directory not found'); // TODO: custom exception
        }

        $finder->files()->name('*.csv')->in($this->etlDataDirectory);
        if (!$finder->hasResults()) {
            throw new \Exception('No CSV file found'); // TODO: custom exception
        }

        foreach ($finder as $file) {
            $this->logger->debug('src\Service\DataLoaderService.php::loadCsv - Loading CSV', [ 'fileName' => $file->getFilename() ]);

            // Get dataSource associated to file
            $dataSource = $this->dataSourceRepository->findOneBy(['label' => $file->getFilename()]);
            if ($dataSource === null) {
                throw new \Exception('File has no associated dataSource'); // TODO: custom exception
            }
            $dataEquivalence = $dataSource->getEquivalence();

            // Get main entity class and repository
            $mainEntityClass = $dataEquivalence['main_entity']['entity'];
            $mainEntityRepository = $this->em->getRepository($mainEntityClass);
            

            // Reading CSV
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);
            $csv->setDelimiter(';'); // a définir dans dataSource ?

            $header = $csv->getHeader();
            $records = $csv->getRecords();
            foreach ($records as $value) {
                
                // Find existing entity
                $criteria = [];
                $identifierField = $dataEquivalence['main_entity']['identified_by'];

                // Entity identified by another entity
                if (is_array($identifierField['type'])) {
                    $subCriteria = [];
                    $subIdentifierField = $identifierField['type'];

                    $subEntityClass = $subIdentifierField['entity'];
                    $subEntityRepository = $this->em->getRepository($subEntityClass);

                    $subCriteria[$subIdentifierField['destination']] = $value[$subIdentifierField['source']];
                    dump($subCriteria);

                    $subEntity = $subEntityRepository->findOneBy($subCriteria);
                    if ($subEntity === null) {
                        $this->logger->debug('src\Service\DataLoaderService.php::loadCsv - Sub entity of ' . $subEntityClass . ' not exists yet. Creating...');

                        $subEntity = new $subEntityClass;
                        $subEntity->__set($subIdentifierField['destination'], $value[$subIdentifierField['source']]);

                        $this->em->persist($subEntity);
                        $this->em->flush();
                    }

                    $criteria[$identifierField['destination']] = $subEntity;

                } else {
                    throw new \Exception('Non-relation identifier not handled yet');
                }

                dump($criteria);

                $mainEntity = $mainEntityRepository->findOneBy($criteria);
                if ($mainEntity === null) {
                    $this->logger->debug('src\Service\DataLoaderService.php::loadCsv - Entity of "' . $mainEntityClass . '" not exists yet. Creating...');
                    $mainEntity = new $mainEntityClass;
                }

                foreach ($dataEquivalence['fields'] as $field) {
                    if (is_array($field['type'])) { // => relation
                        $this->logger->debug('src\Service\DataLoaderService.php::loadCsv - Field "' . $field['destination'] . '" is a relation. Getting it from main entity "' . $mainEntityClass . '"...');

                        // Determine relation type (OneToOne, OneToMany, ManyToOne, ManyToMany)
                        $entityProperty = (new \ReflectionClass($mainEntityClass))->getProperty($field['destination']);
                        $annotations = array_map('get_class', $this->annotationsReader->getPropertyAnnotations($entityProperty)); // Stringify all class name
                        $relationType = array_values(array_intersect(self::MAPPING_RELATIONS, $annotations))[0];

                        $this->logger->debug('src\Service\DataLoaderService.php::loadCsv - Relation type: ' . $relationType);

                        if ($relationType === self::ONE_TO_ONE || $relationType === self::MANY_TO_ONE) {
                            $subEntity = $mainEntity->__get($field['destination']);

                            if ($subEntity === null) {
                                $this->logger->debug('src\Service\DataLoaderService.php::loadCsv - Main entity "' . $mainEntityClass . '" hasn\'t "' . $field['destination'] . '" relation. Getting it from repository...');

                                $subEntity = $this->em->getRepository($field['type']['entity'])->findOneBy([
                                    $field['type']['identified_by']['destination'] => $value[$field['type']['identified_by']['source']],
                                ]);
                                dump($subEntity);

                                if ($subEntity === null) {
                                    throw new \Exception('Sub entity of ' . $field['type']['entity'] . ' not exists yet');
                                }

                                $mainEntity->__set($field['destination'], $subEntity);
                            }

                            $subEntity->__set($field['type']['destination'], $value[$field['type']['source']]);

                        // } 
                        // elseif ($relationType === self::ONE_TO_MANY) {
                        //     throw new \Exception('ONE_TO_MANY relation not handled yet');
                        //     // on part du principe que l'entity existe pas, on la créé et on test si elle existe dans in_array ?

                        // } 
                        // elseif ($relationType === self::MANY_TO_ONE) {
                        //     throw new \Exception('MANY_TO_ONE relation not handled yet');

                        } elseif ($relationType === self::MANY_TO_MANY || $relationType === self::ONE_TO_MANY) { // on la créer et on l'ajoute, normalement y'a pas besoin de la modifier ici
                            $subEntity = $this->em->getRepository($field['type']['entity'])->findOneBy([
                                $field['type']['identified_by']['destination'] => $value[$field['type']['identified_by']['source']],
                            ]);

                            if ($subEntity === null) {
                                $this->logger->debug('src\Service\DataLoaderService.php::loadCsv - Sub entity of ' . $field['type']['entity'] . ' not exists yet. Creating...');

                                $subEntity = new $field['type']['entity'];
                                $subEntity->__set($field['type']['destination'], $value[$field['type']['source']]);

                                $this->em->persist($subEntity);
                            }

                            $mainEntity->__add($field['destination'], $subEntity);
                        }

                    } else {
                        throw new \Exception('Non-relation type is not handled yet');
                    }


                    $this->em->persist($mainEntity); // à déplacer hors du if is_array ?
                    $this->em->flush();

                }
                


            }
        }
    }
}
