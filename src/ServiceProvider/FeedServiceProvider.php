<?php

namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Service\FeedService;

/**
 * Class FeedServiceProvider
 * @package ServiceProvider
 */
class FeedServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * @return FeedService
     */
    public function register(Container $app)
    {
        $app['feedService'] = function() use ($app) {
            $feedService = new FeedService($app['db']);
            foreach ($app['feed.adapters'] as $name => $feedAdapter) {
                $feedService->registerAdapter($name, $feedAdapter);
            }

            return $feedService;
        };
    }
}
