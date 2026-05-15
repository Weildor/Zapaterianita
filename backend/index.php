<?php
require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app = AppFactory::create();

// --- 1. CORS MIDDLEWARE (Obligatorio para React) ---
$app->add(function (Request $request, $handler): Response {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*') 
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
        ->withHeader('Access-Control-Allow-Credentials', 'true');
});

// --- 2. MANEJO DE OPTIONS ---
$app->options('/{routes:.+}', function (Request $request, Response $response) {
    return $response;
});

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

// --- 3. CARGA DE RUTAS ---
require __DIR__ . '/src/Routes/Zapatos.php';

$app->run();