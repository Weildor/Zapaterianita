<?php
use Dorian\Backend\Controllers\ZapatoController;
use Dorian\Backend\Controllers\UsuarioController;
use Dorian\Backend\Middleware\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

// RUTAS PÚBLICAS (No necesitan Token)
$app->post('/api/login', UsuarioController::class . ':login');
$app->post('/api/usuarios', UsuarioController::class . ':saveUsuario'); // <--- LIBRE PARA REGISTRO

// RUTAS PROTEGIDAS (Aquí el AuthMiddleware sí actúa)
$app->group('/api', function (RouteCollectorProxy $group) {
    
    // Zapatos
    $group->get('/zapatos', ZapatoController::class . ':getZapatos');
    $group->post('/zapatos', ZapatoController::class . ':saveZapato');
    $group->get('/zapatos/{id}', ZapatoController::class . ':getZapato');
    $group->put('/zapatos/{id}', ZapatoController::class . ':updateZapato');
    $group->delete('/zapatos/{id}', ZapatoController::class . ':deleteZapato');

    // Usuarios (solo para ver lista si estás logueado)
    $group->get('/usuarios', UsuarioController::class . ':getUsuarios');

})->add(new AuthMiddleware());