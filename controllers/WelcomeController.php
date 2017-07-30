<?php

$app->match('/welcome/', function() use($app) {

    /** @var \Service\WeatherService $weatherService */
    $weatherService = $app['weatherService'];

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    /** @var \Service\NoteService $noteService */
    $noteService = $app['noteService'];

    /** @var \Service\ChecklistService $checklistService */
    $checklistService = $app['checklistService'];
    $todos = $checklistService->getTodos();

    $device = new Mobile_Detect();
    if ($device->isMobile()) {
        return 'Welcome mobile user!';
    }

    return $app['twig']->render('welcome/index.html.twig', [
        'bodyClass' => 'welcome',
        'forecast' => $weatherService->getForecastList(),
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
        'feedItemTotals'=> $feedService->getFeedItemTotals(),
        'notes'=> $noteService->loadNotes(),
        'addedLinks' => $feedService->getFeedItemsBySites([0]),
        'todos' => $todos
    ]);
});

$app->match('/nourl/', function() use($app) {
    return $app['twig']->render('home/no-url.html.twig', [
        'bodyClass' => 'iframe-nolink',
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ]
    ]);
});
