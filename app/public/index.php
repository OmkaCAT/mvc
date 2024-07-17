<?php

use App\Core\Application;
use App\Core\Request;

require __DIR__ . '/../autoload.php';

$app = Application::getInstance();
$app->setBasePath(dirname(__DIR__));
$app->boot();

$request = Request::createFromGlobals();

$response = $app->run($request);

$response->send();

// todo terminate
//$app->terminate($request, $response);
