<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'nelmio_api_doc.generator.default' shared service.

include_once $this->targetDirs[3].'\\vendor\\nelmio\\api-doc-bundle\\ApiDocGenerator.php';

$this->services['nelmio_api_doc.generator.default'] = $instance = new \Nelmio\ApiDocBundle\ApiDocGenerator(new RewindableGenerator(function () {
    yield 0 => ($this->privates['nelmio_api_doc.describers.config'] ?? $this->load('getNelmioApiDoc_Describers_ConfigService.php'));
    yield 1 => ($this->privates['nelmio_api_doc.describers.config.default'] ?? ($this->privates['nelmio_api_doc.describers.config.default'] = new \Nelmio\ApiDocBundle\Describer\ExternalDocDescriber([], true)));
    yield 2 => ($this->privates['nelmio_api_doc.describers.swagger_php.default'] ?? $this->load('getNelmioApiDoc_Describers_SwaggerPhp_DefaultService.php'));
    yield 3 => ($this->privates['nelmio_api_doc.describers.route.default'] ?? $this->load('getNelmioApiDoc_Describers_Route_DefaultService.php'));
    yield 4 => ($this->privates['nelmio_api_doc.describers.default'] ?? ($this->privates['nelmio_api_doc.describers.default'] = new \Nelmio\ApiDocBundle\Describer\DefaultDescriber()));
}, 5), new RewindableGenerator(function () {
    yield 0 => ($this->privates['nelmio_api_doc.model_describers.jms'] ?? $this->load('getNelmioApiDoc_ModelDescribers_JmsService.php'));
    yield 1 => ($this->privates['nelmio_api_doc.model_describers.object'] ?? $this->load('getNelmioApiDoc_ModelDescribers_ObjectService.php'));
}, 2));

$instance->setAlternativeNames([]);

return $instance;
