<?php
/**
 * Created by PhpStorm.
 * User: petervandam
 * Date: 04/03/2017
 * Time: 19:58
 */

$app->match('/droplist/', function() use($app) {

    /** @var \Service\DroplistService $droplistService */
    $droplistService = $app['droplistService'];

    return $app['twig']->render('droplist/list.html.twig', [
        'imageList' => $droplistService->getImages(),
        'limit' => null,
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
        'bodyClass' => 'dropList'
    ]);
});


$app->match('/droplist/{limit}', function($limit) use($app) {

    /** @var \Service\DroplistService $droplistService */
    $droplistService = $app['droplistService'];

    return $app['twig']->render('droplist/index.html.twig', [
        'imageList' => $droplistService->getImages(($limit !== 'all' ? $limit : null)),
        'limit' => $limit,
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../dist/css/style.css'),
            'js_main' => filemtime(__DIR__ . '/../dist/js/app.js'),
        ],
        'bodyClass' => 'dropList'
    ]);
});
