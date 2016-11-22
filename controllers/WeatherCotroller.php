<?php

$app->match('/current-weather/', function() use($app) {

    /** @var \Service\WeatherService $weatherService */
    $weatherService = $app['weatherService'];

    return $app['twig']->render('weather/weathericon.html.twig', [
        'forecast' => $weatherService->getForecastList(),
    ]);
});

$app->match('/weather-page/', function() use($app) {

    /** @var \Service\WeatherService $weatherService */
    $weatherService = $app['weatherService'];

    return $app['twig']->render('weather/weatherpage.html.twig', [
        'forecast' => $weatherService->getForecastList(),
    ]);
});
