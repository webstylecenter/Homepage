<?php

namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Service\WeatherService;

/**
 * Class WeatherServiceProvider
 * @package ServiceProvider
 */
class WeatherServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * @return weatherService
     */
    public function register(Container $app)
    {
        $app['weatherService'] = function() use ($app) {
            return new WeatherService(new $app['weather.adapter']($app['config']), $app['cache']);
        };
    }
}
