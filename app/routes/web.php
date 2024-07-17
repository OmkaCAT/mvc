<?php

/** @var \App\Core\Routing\Router $router */

$router->addRoute(
    ['GET', 'HEAD'],
    '/post',
    [\App\Controllers\PostController::class, 'index']
);

$router->addRoute(
    ['GET'],
    '/post/{id}',
    [\App\Controllers\PostController::class, 'show']
);

$router->addRoute(
    ['POST'],
    '/post/{id}',
    [\App\Controllers\PostController::class, 'update']
);

