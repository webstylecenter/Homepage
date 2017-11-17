<?php

if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'dev') !== false) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
require_once __DIR__ . '/vendor/autoload.php';

(new Raven_Client('https://7198498aafe4456eabd52f2568790ba0:463e1380422c4357a8ff3ef1628c319a@sentry.io/197110'))->install();

$app = new Silex\Application();


require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/services.php';

if (PHP_SAPI === 'cli') {
    set_time_limit(0);
    require_once __DIR__ . '/commands.php';
    $app['console']->run();
} else {
    require_once __DIR__ . '/controller.php';
    require_once __DIR__ . '/middleware.php';
    $app->run();
}
