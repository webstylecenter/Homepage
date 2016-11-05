<?php

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'dbs.options' => array(
        'main' => array(
            'dbname'   => $app['config']['database']['database'],
            'user'     => $app['config']['database']['username'],
            'password' => $app['config']['database']['password'],
            'host'     => $app['config']['database']['host'],
            'driver'   => $app['config']['database']['driver'],
            'charset'  => 'utf8mb4',
        ),
    ),
]);

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../views',
]);

$app['twig'] = $app->extend('twig', function(\Twig_Environment $twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    return $twig;
});

$app->register(new \ServiceProvider\FeedServiceProvider());
$app->register(new \ServiceProvider\WeatherServiceProvider());
