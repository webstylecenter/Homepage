<?php

use Entity\FeedItem;

$app->post('/feed/add-item/', function() use ($app) {
    if (!isset($_POST['description']) || !isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['url']) || empty($_POST['url'])) {
        return json_encode(['status' => 'fail', 'message' => 'Missing parameter(s): title, description, url']);
    }

    $feedItem = new FeedItem(
        intval(time()),
        $_POST['title'],
        $_POST['description'] ?: '',
        strpos($_POST['url'], 'http') === 0 ? $_POST['url'] : 'http://' . $_POST['url'],
        0
    );

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedItem->setPinned(true);
    if (!$feedService->addItem($feedItem)) {
        return $feedService->getLastError();
    }
    return json_encode(['status' => 'success', 'message' => 'Item added']);
});

$app->post('/feed/pin/{id}', function($id) use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedService->pinItem($id);

    return json_encode(['status' => 'success', 'message' => 'Pin toggled']);
});

$app->get('/feed/refresh/{date}', function($date) use ($app) {
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

$app->get('/feed/page/{startIndex}', function($startIndex) use ($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    return $app['twig']->render('home/newsfeed.html.twig', [
        'feedItems'=> $feedService->getFeedItems(50, null, $startIndex),
        'feeds' => $feedService->getFeeds(),
        'nextPageNumber' => $startIndex + 1,
        'addToChecklist' => '',
    ]);
});

$app->get('/feed/search/{startIndex}', function($startIndex) use ($app) {
    $query = isset($_GET['query']) ? $_GET['query'] : null;
    if (!$query) {
        return json_encode(['status' => 'fail', 'message' => 'Missing parameter(s): query']);
    }

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedItems = $feedService->getFeedItems(10, null, $startIndex, $query);

    return json_encode([
        'status' => 'success',
        'data' => array_map(function(FeedItem $feedItem) {
            return [
                'id' => $feedItem->getId(),
                'title' => $feedItem->getTitle(),
                'description' => $feedItem->getShortDescription(),
                'url' => $feedItem->getUrl(),
                'color' => $feedItem->getColor(),
                'feedIcon' => $feedItem->getFeedIcon(),
                'shareId' => $feedItem->getFeedName() . '/' . $feedItem->getId() . '/',
                'pinned' => $feedItem->isPinned()
            ];
        }, $feedItems)
    ]);
});

$app->get('/feeds/overview/', function() use($app) {

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    return $app['twig']->render('widgets/feed-overview.html.twig', [
        'feeds' => $feedService->getFeedOveriew()
    ]);

});

$app->post('/feeds/settingsupdate/', function() use($app) {

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    if (!isset($_POST['setting']) || strlen($_POST['setting']) === 0) {
        throw new Exception("Setting post data not set");
    }
    if (!isset($_POST['value']) || strlen($_POST['value']) === 0) {
        throw new Exception("Value post data not set");
    }
    if (!isset($_POST['feedId']) || strlen($_POST['feedId']) === 0) {
        throw new Exception("feedId post data not set");
    }

    if (!$feedService->updateFeedSetting($_POST['feedId'], $_POST['setting'], $_POST['value'])) {
        return json_encode(['status' => 'fail', 'message' => 'Updating feedsetting failed']);
    }

    return json_encode(['status' => 'success', 'message' => 'Feedsetting updated']);
});
