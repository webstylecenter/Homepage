<?php

$container['database'] = function($container) {
    $config = new \Doctrine\DBAL\Configuration();

    $connectionParams = [
        'dbname' => $container['config']['database']['database'],
        'user' => $container['config']['database']['username'],
        'password' => $container['config']['database']['password'],
        'host' => $container['config']['database']['host'],
        'driver' => $container['config']['database']['driver'],
    ];

    return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
};

$container['twig'] = function ($container) {
    $loader = new Twig_Loader_Filesystem($container['config']['twig']['viewPath']);

    return new Twig_Environment($loader, array(
        'cache' => $container['config']['twig']['cachePath'],
        'debug' => $container['debug']
    ));
};
