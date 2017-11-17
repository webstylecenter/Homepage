<?php

$app->post('/feed/pin/{id}', function($id) use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedService->pinItem($id);

    return json_encode(['status' => 'success', 'message' => 'Pin toggled']);
});
