<?php

$app->post('/chrome/import/', function() use($app) {
    return $app['twig']->render('demo/notice.html.twig');
});
