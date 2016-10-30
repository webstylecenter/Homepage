<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application();

require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/services.php';
require_once __DIR__ . '/controller.php';

$app->run();
