<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private '.service_locator.9P48g_d' shared service.

return $this->privates['.service_locator.9P48g_d'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
    'pf' => ['privates', '.errored..service_locator.9P48g_d.FOS\\RestBundle\\Request\\ParamFetcher', NULL, 'Cannot autowire service ".service_locator.9P48g_d": it references class "FOS\\RestBundle\\Request\\ParamFetcher" but no such service exists. Try changing the type-hint to "FOS\\RestBundle\\Request\\ParamFetcherInterface" instead.'],
]);
