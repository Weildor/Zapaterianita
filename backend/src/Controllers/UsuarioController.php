<?php
namespace Dorian\Backend\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Dorian\Backend\Models\UsuarioModel;

class UsuarioController {
    private $model;

    public function __construct() {
        $this->model = new UsuarioModel();
    }

    public function getUsuarios(Request $req, Response $res) {
        // Llamamos al modelo
        $data = $this->model->listarTodo();
        
        // Escribimos la respuesta en formato JSON
        $res->getBody()->write(json_encode($data));
        return $res->withHeader('Content-type', 'application/json');
    }

    public function login(Request $request, Response $response) {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $resData = $this->model->login($email, $password);
        $response->getBody()->write(json_encode($resData));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function saveUsuario(Request $req, Response $res) {
        $params = json_decode($req->getBody()->getContents(), true);
        $data = $this->model->crear($params);
        $res->getBody()->write(json_encode($data));
        return $res->withHeader('Content-type', 'application/json');
    }
}