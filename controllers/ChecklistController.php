<?php

$app->match('/checklist/', function() use($app) {

    /** @var \Service\ChecklistService $checklistService */
    $checklistService = $app['checklistService'];
    $todos = $checklistService->getTodos();
    $finished = $checklistService->getFinished();

    return $app['twig']->render('checklist/index.html.twig', [
        'bodyClass' => 'checklist',
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
    $todos = $checklistService->getTodos();
    $finished = $checklistService->getFinished();

    return $app['twig']->render('checklist/checklist.html.twig', [
        'todos' => $todos,
        'finished' => $finished
    ]);
});
