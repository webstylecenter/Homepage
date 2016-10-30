<?php

$app->match('/update/', function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    $feedService->import();

    return 'Done';

});
