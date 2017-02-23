<?php

$app->match('/welcome/', function() use($app) {

    /** @var \Service\WeatherService $weatherService */
    $weatherService = $app['weatherService'];

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    /** @var \Service\NoteService $noteService */
    $noteService = $app['noteService'];

    return $app['twig']->render('welcome/index.html.twig', [
        'bodyId' => 'welcome',
        'forecast' => $weatherService->getForecastList(),
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
        'feedItemTotals'=> $feedService->getFeedItemTotals(),
        'note'=> $noteService->loadNote(),
    ]);
});

$app->match('/note/save/', function() use($app) {
    /** @var \Service\NoteService $noteService */
    $noteService = $app['noteService'];
    $noteService->saveNote(
        isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : null,
        isset($_POST['note']) && !empty($_POST['note']) ? $_POST['note'] : '',
        isset($_POST['position']) && !empty($_POST['position']) ? $_POST['position'] : null
    );

    return 'Done';
});
