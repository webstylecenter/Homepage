<?php

$app->get('/share/{feedName}/{id}/', function($feedName, $id) use($app) {

    /** @var \Service\RedirectService $redirectService */
    $redirectService = $app['redirectService'];

    $url = $redirectService->getSharedFeedItem($id, $feedName);
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $url);
    exit;
});
