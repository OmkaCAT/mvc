<?php

/** @var \App\Core\Routing\Router $router */

$router->addRoute(
    ['GET', 'HEAD'],
    '/post',
    [\App\Controllers\PostController::class, 'index']
);

$router->addRoute(
    ['GET'],
    '/post/1',
    [\App\Controllers\PostController::class, 'show']
);

$router->addRoute(
    ['POST'],
    '/post/1',
    [\App\Controllers\PostController::class, 'update']
);

