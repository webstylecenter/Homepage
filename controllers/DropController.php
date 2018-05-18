<?php
/**
 * Created by PhpStorm.
 * User: petervandam
 * Date: 04/03/2017
 * Time: 19:58
 */

$app->match('/droplist/', function() use($app) {
    return $app['twig']->render('demo/notice.html.twig');
});


$app->match('/droplist/{limit}', function($limit) use($app) {
    return $app['twig']->render('demo/notice.html.twig');
});
