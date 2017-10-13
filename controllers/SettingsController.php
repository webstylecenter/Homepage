<?php

$app->get('/settings/', function() use($app) {

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

    if (!isset($_POST['feedId']) || strlen($_POST['feedId']) === 0) {
        exit ('No feed Id given');
    }

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    return $feedService->removeFeed($_POST['feedId']);

});
