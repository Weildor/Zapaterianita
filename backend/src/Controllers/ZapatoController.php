<?php
namespace Dorian\Backend\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Dorian\Backend\Models\ZapatoModel;

class ZapatoController {
    private $model;

    public function __construct(){
        $this->model = new ZapatoModel();
    }

    public function getZapatos(Request $req, Response $res) {
        // CORREGIDO: listar() -> listarTodo()
        $data = $this->model->listarTodo(); 
        $res->getBody()->write(json_encode($data));
        return $res->withHeader('Content-type', 'application/json');
    }

    public function saveZapato(Request $req, Response $res) {
        $params = json_decode($req->getBody()->getContents());
        // CORREGIDO: guardar() -> crear()
        $data = $this->model->crear($params); 
        $res->getBody()->write(json_encode($data));
        return $res->withHeader('Content-type', 'application/json');
    }
}