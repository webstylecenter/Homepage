<?php

$app->match('/', function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    return $app['twig']->render('home/index.html.twig', [
        'feedItems'=> $feedService->getFeedItems(),
        'feedItemTotals'=> $feedService->getFeedItemTotals(),
    ]);
});
