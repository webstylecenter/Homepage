<?php

require_once __DIR__ . '/vendor/autoload.php';

$container = new \Pimple\Container();

require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/services.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $routeCollector) use ($container) {
    $routeCollector->addRoute('GET', '/', 'Home::index');
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::FOUND:
        $controllerAction = explode('::', $routeInfo[1]);
        $controllerName = '\\Controller\\' . ucfirst($controllerAction[0]) . 'Controller';
        $controller = new $controllerName;

        $action = lcfirst($controllerAction[0]) . 'Action';

        if ($controller instanceof \Controller\ContainerAwareInterface) {
            $controller->setContainer($container);
            $response = call_user_func_array(array($controller, $action), $routeInfo[2]);
        }

        break;
}

echo $response;
