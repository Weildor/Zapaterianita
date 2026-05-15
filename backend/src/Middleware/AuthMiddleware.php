<?php
namespace Dorian\Backend\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    // MISMA LLAVE que en el Modelo
    private $secretKey = "CLAVE_MAESTRA_ZAPATERIA_2026_ABC";

    public function __invoke(Request $request, Handler $handler) {
        $authHeader = $request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);

        if (empty($token)) {
            $response = new Response();
            $response->getBody()->write(json_encode(["message" => "Acceso denegado. Token ausente."]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return $handler->handle($request);
        } catch (\Exception $e) {
            $response = new Response();
            $response->getBody()->write(json_encode(["message" => "Token inválido o expirado"]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
    }
}