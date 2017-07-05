<?php

$app->match('/screensaver/', function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedItems = $feedService->getFeedItemsBySites([
        1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 12 // TODO: Make this happen with feed table
    ]);

    if (count($feedItems) === 0) {
        throw new \Exception('No feed items found within a period of the last six hours');
    }

    /** @var \Service\WeatherService $weatherService */
    $weatherService = $app['weatherService'];

    return $app['twig']->render('screensaver/index.html.twig', [
        'bodyId' => 'screensaver',
        'forecast' => $weatherService,
        'feedItems'=> $feedItems,
        'feeds' => $feedService->getFeeds(),
        'firstFeedItem' => $feedItems[0],
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
    ]);
});

$app->match('/screensaver/images/{file}.jpg', function() use($app) {

    if (rand(1, 5) === 1) {
        header('location: '.$app['config']['wallpaperLocations']['private']);
    } else {
        header('location: '.$app['config']['wallpaperLocations']['public']);
    }

    exit;
});
