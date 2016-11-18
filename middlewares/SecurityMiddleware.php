<?php

use \Silex\Application;

$app->before(function() use ($app) {
    if (isset($_COOKIE['jz_auth_succeed'])) {
        return;
    }

    if ($_SERVER['REQUEST_URI'] === '/screensaver/') {
        return;
    }

    if (
        !isset($_SERVER['PHP_AUTH_USER'])
        || !isset($app['users'][$_SERVER['PHP_AUTH_USER']])
        || $app['users'][$_SERVER['PHP_AUTH_USER']] !== $_SERVER['PHP_AUTH_PW']
    ) {
        header('WWW-Authenticate: Basic realm="Login required"');
        return $app->json(['Message' => 'Not authorized'], 401);
    }

    setcookie('jz.auth.succeed', true, time() + 31556926);
    $_COOKIE['jz.auth.succeed'] = true;
}, Application::EARLY_EVENT);
