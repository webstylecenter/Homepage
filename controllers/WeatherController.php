<?php

$app->get('/weather/{type}/', function($type) use($app) {

    /** @var \Service\WeatherService $weatherService */
    $weatherService = $app['weatherService'];

    if (!in_array($type, ['current', 'detail', 'icon'])) {
        $app->abort(404, 'Type not found');
    }
    
    header('Access-Control-Allow-Origin: *');

    return $app['twig']->render('weather/' . $type . '.html.twig', [
        'forecast' => $weatherService->getForecastList(),
    ]);
});
