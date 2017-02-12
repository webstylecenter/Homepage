<?php

use Entity\FeedItem;

$app->match('/update/', function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    $feedService->import();

    return 'Done';

});


$app->post('/add-item/', function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    if (empty($_POST['title']) || empty($_POST['title']) || empty($_POST['url'])) {
        return 'Error: No feed title and/or url entered!';
    }

    $currentTime = (new \DateTime())->format('Y-m-d H:i:s');
    $feedItem = new FeedItem(
        md5($currentTime),
        $_POST['title'],
        $_POST['description'],
        strpos($_POST['url'], 'http') === 0 ? $_POST['url'] : 'http://' . $_POST['url'],
        'userInput'
    );

    $feedItem->setPinned(true);
    if ($feedService->addItem($feedItem)) {
        return 'Done';
    } else {
        return $feedService->getLastError();
    }

});

$app->match('/refresh/{date}', function($date) use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    return json_encode([
        'html' => $app['twig']->render('home/newsfeed.html.twig', [
                    'feedItems'=> $feedService->getFeedItems(999, new \DateTime($date)),
                    'nextPageNumber' => 50000,
                    'addToChecklist' => '',
                ]),
        'refreshDate' => (new \DateTime())->format('Y-m-d H:i:s'),
    ]);
});

$app->match('/page/{number}', function($number) use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    return $app['twig']->render('home/newsfeed.html.twig', [
        'feedItems'=> $feedService->getFeedItems(50, null, $number),
        'nextPageNumber' => $number + 1,
        'addToChecklist' => '',
    ]);
});

$app->match('/search/{query}', function($query) use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    return $app['twig']->render('home/newsfeed.html.twig', [
        'feedItems'=> $feedService->getFeedItems(10, null, null, $query),
        'nextPageNumber' => 99999,
        'addToChecklist' => $query,
    ]);
});
