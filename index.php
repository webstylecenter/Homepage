<?php

chdir(__DIR__);

session_start();

require_once __DIR__ . '/vendor/autoload.php';

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
