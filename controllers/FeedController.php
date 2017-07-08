<?php

use Entity\FeedItem;

$app->match('/update/', function() use ($app) {
    $app['feedService']->import();

    return 'Done';
});


$app->post('/add-item/', function() use ($app) {
    if (!isset($_POST['description']) || !isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['url']) || empty($_POST['url'])) {
        return 'Error: No feed title and/or url provided!';
    }

    $feedItem = new FeedItem(
        md5((new \DateTime())->format('Y-m-d H:i:s')),
        $_POST['title'],
        $_POST['description'] ?: null,
        strpos($_POST['url'], 'http') === 0 ? $_POST['url'] : 'http://' . $_POST['url'],
        'userInput'
    );

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedItem->setPinned(true);
    if (!$feedService->addItem($feedItem)) {
        return $feedService->getLastError();
    }
    return 'Done';
});

$app->get('/refresh/{date}', function($date) use ($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    return json_encode([
        'html' => $app['twig']->render('home/newsfeed.html.twig', [
            'feedItems'=> $feedService->getFeedItems(999, new \DateTime($date)),
            'feeds' => $feedService->getFeeds(),
            'nextPageNumber' => 50000,
            'addToChecklist' => '',
        ]),
        'refreshDate' => (new \DateTime())->format('Y-m-d H:i:s'),
    ]);
});

$app->get('/page/{number}', function($number) use ($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    return $app['twig']->render('home/newsfeed.html.twig', [
        'feedItems'=> $feedService->getFeedItems(50, null, $number),
        'feeds' => $feedService->getFeeds(),
        'nextPageNumber' => $number + 1,
        'addToChecklist' => '',
    ]);
});

$app->get('/search/{query}', function($query) use ($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    return $app['twig']->render('home/newsfeed.html.twig', [
        'feedItems'=> $feedService->getFeedItems(10, null, null, $query),
        'feeds' => $feedService->getFeeds(),
        'nextPageNumber' => 99999,
        'addToChecklist' => $query,
    ]);
});
