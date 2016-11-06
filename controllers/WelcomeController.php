<?php

$app->match('/welcome/', function() use($app) {

    /** @var \Service\WeatherService $weatherService */
    $weatherService = $app['weatherService'];

    return $app['twig']->render('welcome/index.html.twig', [
        'forecast' => $weatherService->getForecastList(),
    ]);
});
