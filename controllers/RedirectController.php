<?php

$app->get('/share/{id}-{feedId}', function($id, $feedId) use($app) {

    /** @var \Service\RedirectService $redirectService */
    $redirectService = $app['redirectService'];

    $url = $redirectService->getSharedFeedItem($id, $feedId);
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $url);
    exit;
});
