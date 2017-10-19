<?php

namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Service\DroplistService;

/**
 * Class DroplistServiceProvider
 * @package ServiceProvider
 */
class DroplistServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * @return droplistService
     */
    public function register(Container $app)
    {
        $app['droplistService'] = function() use ($app) {
            return new DroplistService($app['config']);
        };
    }
}
