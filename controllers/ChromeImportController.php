<?php

use Entity\FeedItem;

$app->post('/chrome/import/', function() use($app) {
    if (!isset($_POST['url'])) {
        return json_encode([
            'status' => 'fail',
            'message' => 'Missing parameter(s): url'
        ]);
    }

    $metaData = (new \Service\MetaService)->getByUrl($_POST['url']);

    $feedItem = new FeedItem(
        md5((new \DateTime())->format('Y-m-d H:i:s')),
        $metaData->getTitle(),
        $metaData->getMetaDescription() ?: '',
        $metaData->getUrl(),
        0
    );
    $feedItem->setPinned(true);

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    if (!$feedService->addItem($feedItem)) {
        return json_encode([
            'status' => 'fail',
            'message' => $feedService->getLastError()
        ]);
    }

    return json_encode([
        'status' => 'success',
        'data' => [
            'title' => $metaData->getTitle(),
            'description' => $metaData->getMetaDescription() ?: '',
            'url' => $metaData->getUrl(),
        ]
    ]);
});
