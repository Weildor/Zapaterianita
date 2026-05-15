<?php
namespace Dorian\Backend\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Dorian\Backend\Models\ZapatoModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ZapatoController {
    private $model;
    private $secretKey = "CLAVE_MAESTRA_ZAPATERIA_2026_ABC";

    public function __construct(){
        $this->model = new ZapatoModel();
    }

    private function getUsuarioIdDesdeToken(Request $req) {
        $authHeader = $req->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return $decoded->data->id; 
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getZapatos(Request $req, Response $res) {
        $data = $this->model->listarTodo(); 
        $res->getBody()->write(json_encode($data));
        return $res->withHeader('Content-type', 'application/json');
    }

    public function saveZapato(Request $req, Response $res) {
        $params = json_decode($req->getBody()->getContents());
        $id_usuario = $this->getUsuarioIdDesdeToken($req);
        
        $data = $this->model->crear($params, $id_usuario); 
        
        $res->getBody()->write(json_encode($data));
        return $res->withHeader('Content-type', 'application/json');
    }

    public function updateZapato(Request $req, Response $res, $args) {
        $id = $args['id'];
        $params = json_decode($req->getBody()->getContents());
        $id_usuario = $this->getUsuarioIdDesdeToken($req);
        
        $data = $this->model->actualizar($id, $params, $id_usuario);
        
        $res->getBody()->write(json_encode($data));
        return $res->withHeader('Content-type', 'application/json');
    }

    public function getZapato(Request $req, Response $res, $args) {
        $id = $args['id']; 
        $data = $this->model->obtenerPorId($id);
        $res->getBody()->write(json_encode($data));
        return $res->withHeader('Content-type', 'application/json');
    }

    public function deleteZapato(Request $req, Response $res, $args) {
        $id = $args['id']; 
        $data = $this->model->eliminar($id);
        $res->getBody()->write(json_encode($data));
        return $res->withHeader('Content-type', 'application/json');
    }
}