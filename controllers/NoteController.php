<?php

$app->post('/note/save/', function() use($app) {
    return $app['twig']->render('demo/notice.html.twig');
});

$app->post('/note/remove/', function() use($app) {
    return $app['twig']->render('demo/notice.html.twig');
});
