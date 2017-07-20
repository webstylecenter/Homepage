<?php

$app->get('/', function() use($app) {
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

    $feedService->markAllViewed();

    return $app['twig']->render($templateFolder . '/index.html.twig', [
        'bodyClass' => $bodyClass,
        'feedItems'=> $feedItems,
        'feeds' => $feedService->getFeeds(),
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
