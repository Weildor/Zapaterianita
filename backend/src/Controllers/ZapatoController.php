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
    public function deleteZapato($req, $res, $args) {
    // $args['id'] captura el número que pongas en la URL
    $id = $args['id']; 
    $data = $this->model->eliminar($id);
    
    $res->getBody()->write(json_encode($data));
    return $res->withHeader('Content-type', 'application/json');
}
    public function getZapato($req, $res, $args) {
    $id = $args['id']; 
    $data = $this->model->obtenerPorId($id);
    
    $res->getBody()->write(json_encode($data));
    return $res->withHeader('Content-type', 'application/json');
}
public function updateZapato(Request $req, Response $res, $args) {
    $id = $args['id'];
    $params = json_decode($req->getBody()->getContents());
    
    $data = $this->model->actualizar($id, $params);
    
    $res->getBody()->write(json_encode($data));
    return $res->withHeader('Content-type', 'application/json');
}
}