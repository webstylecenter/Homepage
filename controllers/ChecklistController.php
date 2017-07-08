<?php

$app->match('/checklist/', function() use($app) {

    /** @var \Service\ChecklistService $checklistService */
    $checklistService = $app['checklistService'];
    $todos = $checklistService->getTodos();
    $finished = $checklistService->getFinished();

    return $app['twig']->render('checklist/index.html.twig', [
        'bodyId' => 'checklist',
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
        'todos' => $todos,
        'finished' => $finished
    ]);
});

$app->match('/checklist/add/', function() use($app) {

    /** @var \Service\ChecklistService $checklistService */
    $checklistService = $app['checklistService'];
    $checklistService->saveChecklistItem(
        isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : null,
        isset($_POST['item']) && !empty($_POST['item']) ? $_POST['item'] : '',
        isset($_POST['checked']) && $_POST['checked'] === 'true'
    );

    $todos = $checklistService->getTodos();
    $finished = $checklistService->getFinished();

    return $app['twig']->render('checklist/checklist.html.twig', [
        'todos' => $todos,
        'finished' => $finished
    ]);
});

$app->match('/test/', function() use($app) {
    return $app['twig']->render('checklist/temp.html.twig', [
        'bodyId' => 'test',
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ]
    ]);
});
