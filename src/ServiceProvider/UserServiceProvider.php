<?php

namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Service\UserService;

/**
 * Class UserServiceProvider
 * @package ServiceProvider
 */
class UserServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * @return UserService
     */
    public function register(Container $app)
    {
        $app['userService'] = function() use ($app) {
            return new UserService($app['db'], $app['config']);
        };
    }
}
