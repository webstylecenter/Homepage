<?php

$app->match('/m/', function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    return $app['twig']->render('mobile/index.html.twig', [
        'feedItems'=> $feedService->getFeedItems(),
        'feedItemTotals'=> $feedService->getFeedItemTotals(),
    ]);
});
