<?php

$homeController = function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    $templateFolder = $_SERVER['REQUEST_URI'] === '/m/' ? 'mobile' : 'home';
    return $app['twig']->render($templateFolder . '/index.html.twig', [
        'feedItems'=> $feedService->getFeedItems(),
        'feedItemTotals'=> $feedService->getFeedItemTotals(),
    ]);
};

$app->get('/', $homeController);
$app->get('/m/', $homeController);
