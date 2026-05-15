<?php
use Dorian\Backend\Controllers\ZapatoController;
use Dorian\Backend\Controllers\UsuarioController;
use Slim\Routing\RouteCollectorProxy;

$app->group('/api/', function (RouteCollectorProxy $group) {
    $group->get('zapatos', ZapatoController::class.':getZapatos');
    $group->post('zapatos', ZapatoController::class.':saveZapato');
    // Aquí puedes añadir PUT y DELETE siguiendo el mismo esquema
    // Ruta de usuarios (NUEVA)
    $group->get('usuarios', UsuarioController::class.':getUsuarios');
    $group->post('usuarios', UsuarioController::class.':saveUsuario'); // <-- Verifica que esta línea esté aquí
});