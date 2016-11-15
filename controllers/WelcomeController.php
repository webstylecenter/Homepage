<?php

$app->match('/welcome/', function() use($app) {

    /** @var \Service\WeatherService $weatherService */
    $weatherService = $app['weatherService'];

    return $app['twig']->render('welcome/index.html.twig', [
        'forecast' => $weatherService->getForecastList(),
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../assets/css/style.css'),
            'css_mobile' => filemtime(__DIR__ . '/../assets/css/mobile.css'),
            'js_main' => filemtime(__DIR__ . '/../assets/scripts/main.js'),
            'js_mobile' => filemtime(__DIR__ . '/../assets/scripts/mobile.js'),
        ],
    ]);
});
