<?php

$app->match('/welcome/', function() use($app) {
    return $app['twig']->render('welcome/index.html.twig', [

    ]);
});
