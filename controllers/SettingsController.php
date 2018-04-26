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

    if (!isset($_POST['feedId']) || strlen($_POST['feedId']) === 0) {
        exit ('No feed Id given');
    }

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    return $feedService->removeFeed($_POST['feedId']);
});

$app->post('/settings/feeds/add/', function() use($app) {

    if (!isset($_POST['name']) || strlen($_POST['name']) === 0 || !isset($_POST['url']) || strlen($_POST['url']) === 0 || !isset($_POST['color']) || strlen($_POST['color']) === 0) {
        exit ('Error: Not all feed details entered');
    }

    $_POST['color'] = substr($_POST['color'], 1);

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    return $feedService->addFeed($_POST['name'], $_POST['url'], $_POST['color'], $_POST['icon']);
});
