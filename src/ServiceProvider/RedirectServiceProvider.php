<?php

namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Service\RedirectService;

/**
 * Class RedirectServiceProvider
 * @package RedirectService
 */
class RedirectServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * @return RedirectService
     */
    public function register(Container $app)
    {
        $app['redirectService'] = function() use ($app) {
            return new RedirectService($app['db']);
        };
    }
}
