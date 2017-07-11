<?php

$homeController = function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedItems = $feedService->getFeedItems();

    if ($_SERVER['REQUEST_URI'] === '/m/') {
        $templateFolder = 'mobile';
        $bodyId = 'mobile';
    } else {
        $templateFolder = 'home';
        $bodyId = 'homepage';
    }

    $feedService->markAllViewed();

    return $app['twig']->render($templateFolder . '/index.html.twig', [
        'bodyId' => $bodyId,
        'feedItems'=> $feedItems,
        'feeds' => $feedService->getFeeds(),
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
        'nextPageNumber' => 2,
    ]);
};

$app->get('/', $homeController);
$app->get('/m/', $homeController);

$app->get('/offline/', function() use($app) {
    return $app['twig']->render('home/offline.html.twig', [
        'bodyId' => 'offline',
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
    ]);
});
