<?php

$app->match('/screensaver/', function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedItems = $feedService->getFeedItems();
    $feedItemTotals = $feedService->getFeedItemTotals();

    return $app['twig']->render('screensaver/index.html.twig', [
        'feedItems'=> $feedItems,
        'feedItemTotals'=> $feedItemTotals,
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../assets/css/style.css'),
            'css_mobile' => filemtime(__DIR__ . '/../assets/css/mobile.css'),
            'js_main' => filemtime(__DIR__ . '/../assets/scripts/main.js'),
            'js_mobile' => filemtime(__DIR__ . '/../assets/scripts/mobile.js'),
        ],
    ]);
});
