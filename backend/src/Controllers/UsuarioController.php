<?php
namespace Dorian\Backend\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Dorian\Backend\Models\UsuarioModel;

class UsuarioController {
    private $model;

    public function __construct(){
        $this->model = new UsuarioModel();
    }

    public function getUsuarios(Request $req, Response $res) {
        $data = $this->model->listarTodo();
        $res->getBody()->write(json_encode($data));
        return $res->withHeader('Content-type', 'application/json');
    }
    public function saveUsuario(Request $req, Response $res) {
        $params = json_decode($req->getBody()->getContents());
        $data = $this->model->crear($params);
        $res->getBody()->write(json_encode($data));
        return $res->withHeader('Content-type', 'application/json');
    }
}