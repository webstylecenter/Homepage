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
