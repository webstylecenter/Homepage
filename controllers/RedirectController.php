<?php

$app->get('/share/{feedName}/{id}/', function($feedName, $id) use($app) {

    /** @var \Service\RedirectService $redirectService */
    $redirectService = $app['redirectService'];

    $url = $redirectService->getSharedFeedItem($id);
    return $app->redirect($url);
});
