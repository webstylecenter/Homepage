<?php

use \Silex\Application;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

$app->before(function() use ($app) {

    $publicPages = [
        '/contact/',
        '/screensaver/',
        '/share/',
        '/weather/',
        '/meta/',
        '/chrome/',
        '/signin/'
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

    return $app->redirect('/signin/');
}, Application::EARLY_EVENT);
