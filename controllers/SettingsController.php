<?php

$app->match('/settings/', function() use($app) {

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feeds = $feedService->getFeeds();
    unset($feeds[0]);

    return $app['twig']->render('settings/index.html.twig', [
        'bodyClass' => 'settings',
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
        'feeds' => $feeds,
        'feedtotals' => $feedService->getFeedItemTotals()
    ]);
});

$app->post('/settings/feeds/remove/', function() use($app) {
    return $app['twig']->render('demo/notice.html.twig');
});

$app->post('/settings/feeds/add/', function() use($app) {
    return $app['twig']->render('demo/notice.html.twig');
});
