<?php
// backend/index.php
require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

// 1. Activar el enrutamiento (Indispensable en Slim 4)
$app->addRoutingMiddleware();

// 2. Activar el Middleware de Errores de Slim 
// Esto interceptará los errores 500 y te dirá exactamente qué falló
// Parámetros: mostrarDetalles, guardarLogs, guardarDetallesDeLogs
$app->addErrorMiddleware(true, true, true);

// Cabeceras CORS para React
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Carga las rutas
require __DIR__ . '/src/Routes/Zapatos.php';

$app->run();