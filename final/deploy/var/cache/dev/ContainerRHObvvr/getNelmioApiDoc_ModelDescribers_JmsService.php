<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'nelmio_api_doc.model_describers.jms' shared service.

include_once $this->targetDirs[3].'\\vendor\\nelmio\\api-doc-bundle\\ModelDescriber\\ModelDescriberInterface.php';
include_once $this->targetDirs[3].'\\vendor\\nelmio\\api-doc-bundle\\Describer\\ModelRegistryAwareInterface.php';
include_once $this->targetDirs[3].'\\vendor\\nelmio\\api-doc-bundle\\Describer\\ModelRegistryAwareTrait.php';
include_once $this->targetDirs[3].'\\vendor\\nelmio\\api-doc-bundle\\ModelDescriber\\JMSModelDescriber.php';

return $this->privates['nelmio_api_doc.model_describers.jms'] = new \Nelmio\ApiDocBundle\ModelDescriber\JMSModelDescriber(($this->privates['jms_serializer.metadata_factory'] ?? $this->load('getJmsSerializer_MetadataFactoryService.php')), NULL, ($this->privates['annotations.cached_reader'] ?? $this->getAnnotations_CachedReaderService()));
