<?php

require_once __DIR__ . '/vendor/autoload.php';

(new Raven_Client('https://7198498aafe4456eabd52f2568790ba0:463e1380422c4357a8ff3ef1628c319a@sentry.io/197110'))->install();

$app = new Silex\Application();

require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/services.php';
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/middleware.php';

$app->run();
