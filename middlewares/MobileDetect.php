<?php

use \Silex\Application;

$app->before(function() use ($app) {

    $detect = new Mobile_Detect();
    if ($detect->isMobile() && $_SERVER['REQUEST_URI'] === '/') {
        header('location: /m/');
        exit;
    }

}, Application::EARLY_EVENT);
