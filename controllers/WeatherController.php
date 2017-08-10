<?php

$app->get('/weather/{type}/', function($type) use($app) {

    /** @var \Service\WeatherService $weatherService */
    $weatherService = $app['weatherService'];

    if (!in_array($type, ['current', 'detail', 'icon'])) {
        $app->abort(404, 'Type not found');
    }

    return $app['twig']->render('weather/' . $type . '.html.twig', [
        'forecast' => $weatherService->getForecastList(),
    ]);
});

$app->get('/weather/update', function() use($app) {
    /** @var \Service\WeatherService $weatherService */
    $weatherService = $app['weatherService'];
    return $weatherService->updateForecast();
});

