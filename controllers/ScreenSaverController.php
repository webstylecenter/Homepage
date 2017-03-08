<?php

$app->match('/screensaver/', function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedItems = $feedService->getFeedItemsBySites([
        'NOS', 'Geenstijl', 'Neowin', 'Gamersnet', 'iDownloadblog', 'MajorNelson'
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
        'firstFeedItem' => $feedItems[0],
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
    ]);
});

$app->match('/screensaver/images/{file}.jpg', function() use($app) {

    header('location: https://drop.petervdam.nl/screensaver/');
    exit;

    if (rand(1, 5) === 1) {
        header('location: https://drop.petervdam.nl/screensaver/');
    } else {
        header('location: https://source.unsplash.com/category/nature/1920x1080');
    }

    exit;
});
