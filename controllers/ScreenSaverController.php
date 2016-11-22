<?php

$app->match('/screensaver/', function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedItems = $feedService->getFeedItems(50, (new DateTime())->setTime(-6, 0));

    if (count($feedItems) == 0) {
        throw new \Exception("No feed items added in the past 6 hours, please make sure your cronjob is updating feeds using the /update/ url");
    }

    /** @var \Service\WeatherService $weatherService */
    $weatherService = $app['weatherService'];

    return $app['twig']->render('screensaver/index.html.twig', [
        'forecast' => $weatherService->getForecastList(),
        'feedItems'=> $feedItems,
        'firstFeedItem' => $feedItems[0],
        'secondFeedItem' => $feedItems[1],
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../assets/css/style.css'),
            'css_mobile' => filemtime(__DIR__ . '/../assets/css/mobile.css'),
            'css_screensaver' => filemtime(__DIR__ . '/../assets/css/screensaver.css'),
            'js_main' => filemtime(__DIR__ . '/../assets/scripts/main.js'),
            'js_mobile' => filemtime(__DIR__ . '/../assets/scripts/mobile.js'),
            'js_screensaver' => filemtime(__DIR__ . '/../assets/scripts/screensaver.js'),
        ],
    ]);
});

$app->match('/screensaver/images/{file}.jpg', function() use($app) {
    header('location: https://source.unsplash.com/category/nature/3840x2160');
    exit;
});
