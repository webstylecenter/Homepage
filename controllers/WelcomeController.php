<?php

$app->match('/welcome/', function() use($app) {

    $weatherService = $app['weatherService'];

    return $app['twig']->render('welcome/index.html.twig', [
        'forecast' => $weatherService->getForecast(),
    ]);
});
