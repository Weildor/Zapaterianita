<?php
use Dorian\Backend\Controllers\ZapatoController;
use Dorian\Backend\Controllers\UsuarioController;
use Dorian\Backend\Middleware\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

// LOGIN: Siempre público
$app->post('/api/login', UsuarioController::class . ':login');

// RUTAS BAJO LLAVE: Requieren Token
$app->group('/api', function (RouteCollectorProxy $group) {
    
    // Zapatos
    $group->get('/zapatos', ZapatoController::class . ':getZapatos');
    $group->post('/zapatos', ZapatoController::class . ':saveZapato');
    $group->get('/zapatos/{id}', ZapatoController::class . ':getZapato');
    $group->put('/zapatos/{id}', ZapatoController::class . ':updateZapato');
    $group->delete('/zapatos/{id}', ZapatoController::class . ':deleteZapato');

    // Usuarios
    $group->get('/usuarios', UsuarioController::class . ':getUsuarios');
    $group->post('/usuarios', UsuarioController::class . ':saveUsuario');

})->add(new AuthMiddleware());