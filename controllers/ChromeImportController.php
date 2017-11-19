<?php

use Entity\FeedItem;

$app->get('/chrome/import/', function() use($app) {

    $url = urldecode($_GET['url']);

    $meta = new \Service\MetaService();
    $metaData = $meta->getByUrl($url);

    $feedItem = new FeedItem(
        md5((new \DateTime())->format('Y-m-d H:i:s')),
        $metaData->getTitle(),
        $metaData->getMetaDescription() ?: '',
        $url,
        0
    );

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedItem->setPinned(true);
    if (!$feedService->addItem($feedItem)) {
        return $feedService->getLastError();
    }


    return $app['twig']->render('chrome/imported.html.twig', [
        'import' => [
            'title' => $metaData->getTitle(),
            'description' => $metaData->getMetaDescription() ?: '',
            'url' => $url,
        ],
        'bodyClass' => 'chromeImport',
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
    ]);

});
