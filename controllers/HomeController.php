<?php

$homeController = function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedItems = $feedService->getFeedItems();
    $feedItemTotals = $feedService->getFeedItemTotals();

    if ($_SERVER['REQUEST_URI'] === '/m/') {
        $templateFolder = 'mobile';
    } else {
        $templateFolder = 'home';
        $feedService->markAllViewed();
    }

    return $app['twig']->render($templateFolder . '/index.html.twig', [
        'feedItems'=> $feedItems,
        'feedItemTotals'=> $feedItemTotals,
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../assets/css/style.css'),
            'css_mobile' => filemtime(__DIR__ . '/../assets/css/mobile.css'),
            'js_main' => filemtime(__DIR__ . '/../assets/scripts/main.js'),
            'js_mobile' => filemtime(__DIR__ . '/../assets/scripts/mobile.js'),
        ],
    ]);
};

$app->get('/', $homeController);
$app->get('/m/', $homeController);
