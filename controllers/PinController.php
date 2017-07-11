<?php

$app->get('/feed/pin/{id}', function($id) use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    return $feedService->pinItem($id);
});
