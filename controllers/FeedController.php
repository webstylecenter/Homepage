<?php

$app->match('/update/', function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    $feedService->import();

    return 'Done';

});


$app->post('/add-item/', function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    return $feedService->addItem($_POST);

});

$app->match('/refresh/{date}', function($date) use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    return json_encode([
        'html' => $app['twig']->render('home/newsfeed.html.twig', [
                    'feedItems'=> $feedService->getFeedItems(999, $date),
                ]),
        'refreshDate' => (new \DateTime())->format('Y-m-d H:i:s'),
    ]);
});
