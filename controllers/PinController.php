<?php

$app->match('/pin/{id}', function($id) use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    return $feedService->pinItem($id);
});
