<?php

$app->match('/welcome/', function() use($app) {

    $key = $app['config']['weatherApi']['key'];
    $location = $app['config']['weatherApi']['location'];
    $units = $app['config']['weatherApi']['units'];

    $weather = json_decode(
        file_get_contents(
            'http://api.openweathermap.org/data/2.5/weather?q=' . $location . '&APPID=' . $key . '&units=' . $units
        ),
        true
    );

    $forecast = [
        'today' => [
            'type' => $weather['weather'][0]['main'],
            'description' => $weather['weather'][0]['description']
        ],
        'tomorrow' => [
            'type' => isset($weather['weather'][1]) ? $weather['weather'][1]['main'] : 0,
            'description' => isset($weather['weather'][1]) ? $weather['weather'][1]['description'] : 0,
        ],
        'temp' => $weather['main']['temp'],
        'temp_max' => $weather['main']['temp_max'],
        'temp_min' => $weather['main']['temp_min'],
    ];

    return $app['twig']->render('welcome/index.html.twig', [
        'forecast' => $forecast,
    ]);
});
