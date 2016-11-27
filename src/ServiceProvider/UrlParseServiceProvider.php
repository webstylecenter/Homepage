<?php

namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Service\UrlParseService;

/**
 * Class UrlParseServiceProvider
 * @package ServiceProvider
 */
class UrlParseServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * @return urlParseService
     */
    public function register(Container $app)
    {
        $app['urlParseService'] = function() use ($app) {
            return new UrlParseService();
        };
    }
}
