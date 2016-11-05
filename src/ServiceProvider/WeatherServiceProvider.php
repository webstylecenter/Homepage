<?php

namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Service\weatherService;

class WeatherServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * @return weatherService
     */
    public function register(Container $app)
    {
        $app['weatherService'] = function() use ($app) {
            $weatherService = new weatherService($app['config']['weatherApi']);
            return $weatherService;
        };
    }
}
