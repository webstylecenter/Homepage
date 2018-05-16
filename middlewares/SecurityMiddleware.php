<?php

use \Silex\Application;

$app->before(function() use ($app) {

    $publicPages = [
        '/contact/',
        '/screensaver/',
        '/share/',
        '/weather/',
        '/meta/',
        '/chrome/'
    ];

    foreach ($publicPages as $page) {
        if (str_replace($page, '', $_SERVER['REQUEST_URI']) !== $_SERVER['REQUEST_URI']) {
            return;
        }
    }

    if (in_array($_SERVER['REQUEST_URI'], $publicPages)) {
        return;
    }

    /** @var \Service\UserService $userService */
    $userService = $app['userService'];

    if ($userService->hasSignedIn()) {
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($userService->signIn($_POST)) {
            if ($_SERVER['REQUEST_URI'] == '/login/') {
                return $app->redirect('/');
            }

            return;
        }
    }

    echo $app['twig']->render('guests/index.html.twig', [
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
        'bodyClass' => 'error403'
    ]);

}, Application::EARLY_EVENT);
