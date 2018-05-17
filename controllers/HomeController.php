<?php

$app->match('/', function() use($app) {

    /** @var \Service\WeatherService $weatherService */
    $weatherService = $app['weatherService'];

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedItems = $feedService->getFeedItems();

    if ($_SERVER['REQUEST_URI'] === '/m/') {
        $templateFolder = 'mobile';
        $bodyClass = 'mobile';
    } else {
        $templateFolder = 'home';
        $bodyClass = 'homepage';
    }

    /** @var $device */
    $device = new Mobile_Detect();

    $feedService->markAllViewed();

    return $app['twig']->render($templateFolder . '/index.html.twig', [
        'bodyClass' => $bodyClass,
        'forecast' => $weatherService->getForecastList(),
        'feedItems'=> $feedItems,
        'feeds' => $feedService->getFeeds(),
        'device' => $device,
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
        'nextPageNumber' => 2,
    ]);
});

$app->get('/offline/', function() use($app) {
    return $app['twig']->render('home/offline.html.twig', [
        'bodyClass' => 'offline',
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
    ]);
});

$app->get('/signin/', function() use($app) {
    return $app['twig']->render('guests/index.html.twig', [
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
        'bodyClass' => 'error403'
    ]);
});
