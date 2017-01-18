<?php

namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Service\NoteService;

/**
 * Class NoteServiceProvider
 * @package ServiceProvider
 */
class NoteServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * @return NoteService
     */
    public function register(Container $app)
    {
        $app['noteService'] = function() use ($app) {
            return new NoteService($app['db']);
        };
    }
}
