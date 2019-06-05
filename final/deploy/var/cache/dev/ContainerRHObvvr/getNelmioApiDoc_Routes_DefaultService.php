<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'nelmio_api_doc.routes.default' shared service.

include_once $this->targetDirs[3].'\\vendor\\symfony\\routing\\RouteCollection.php';
include_once $this->targetDirs[3].'\\vendor\\nelmio\\api-doc-bundle\\Routing\\FilteredRouteCollectionBuilder.php';

return $this->privates['nelmio_api_doc.routes.default'] = (new \Nelmio\ApiDocBundle\Routing\FilteredRouteCollectionBuilder(($this->privates['annotations.cached_reader'] ?? $this->getAnnotations_CachedReaderService()), ($this->privates['nelmio_api_doc.controller_reflector'] ?? $this->load('getNelmioApiDoc_ControllerReflectorService.php')), 'default', ['path_patterns' => [0 => '^/(?!api/doc)'], 'host_patterns' => [], 'with_annotation' => false]))->filter(($this->services['router'] ?? $this->getRouterService())->getRouteCollection());
