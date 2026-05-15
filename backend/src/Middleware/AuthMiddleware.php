<?php
namespace Dorian\Backend\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response as SlimResponse;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    // IMPORTANTE: Esta llave debe ser la misma que usas en UsuarioModel para generar el token
    private $secretKey = "CLAVE_MAESTRA_ZAPATERIA_2026_ABC";

    public function __invoke(Request $request, Handler $handler): Response {
        // 1. Obtener el encabezado Authorization
        $authHeader = $request->getHeaderLine('Authorization');
        
        // 2. Extraer el token (formato: "Bearer [token]")
        $token = str_replace('Bearer ', '', $authHeader);

        // 3. Si no hay token, denegar acceso inmediatamente
        if (empty($token)) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode([
                "response" => false,
                "message" => "Acceso denegado. Token ausente o inválido."
            ]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        try {
            // 4. Intentar decodificar el token con la llave secreta
            // Si el token expiró o fue manipulado, esto lanzará una Exception
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));

            // 5. Si todo está bien, continuar con la petición
            return $handler->handle($request);

        } catch (\Exception $e) {
            // 6. Si el token es inválido (expirado, firma incorrecta, etc.)
            $response = new SlimResponse();
            $response->getBody()->write(json_encode([
                "response" => false,
                "message" => "Sesión inválida o expirada. Por favor, inicia sesión de nuevo."
            ]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
    }
}