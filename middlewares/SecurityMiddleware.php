<?php

use \Silex\Application;

$app->before(function() use ($app) {
    if (isset($_COOKIE['jz_auth_succeed'])) {
        return;
    }

    if ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] == '/droplist/') {
        if (
            !isset($_SERVER['PHP_AUTH_USER'])
            || !isset($app['users'][$_SERVER['PHP_AUTH_USER']])
            || $app['users'][$_SERVER['PHP_AUTH_USER']] !== $_SERVER['PHP_AUTH_PW']
        ) {
            header('WWW-Authenticate: Basic realm="Login required"');

            echo $app['twig']->render('guests/index.html.twig', [
                'lastUpdate' => [
                    'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
                    'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
                ],
                'bodyId' => 'error403'
            ]);
            exit;
        }

        setcookie('jz.auth.succeed', true, time() + 31556926);
        $_COOKIE['jz.auth.succeed'] = true;
    }

    if ($_SERVER['REQUEST_URI'] === '/screensaver/' || $_SERVER['REQUEST_URI'] === '/refresh/') {
        return;
    }

    if (strpos($_SERVER['REQUEST_URI'], '/feed/refresh/') !== false) {
        return;
    }

}, Application::EARLY_EVENT);
