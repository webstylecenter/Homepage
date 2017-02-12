<?php

namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Service\ChecklistService;

/**
 * Class ChecklistServiceProvider
 * @package ServiceProvider
 */
class ChecklistServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * @return checklistService
     */
    public function register(Container $app)
    {
        $app['checklistService'] = function() use ($app) {
            return new ChecklistService($app['db']);
        };
    }
}
