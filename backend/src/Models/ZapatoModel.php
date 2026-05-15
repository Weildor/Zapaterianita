<?php
namespace Dorian\Backend\Models;

use Dorian\Backend\Lib\Response;

class ZapatoModel {
    private $db;
    private $response;

    public function __construct() {
        $mysql = new MysqlModel();
        $this->db = $mysql->sqlPDO; 
        $this->response = new Response();
    }

    public function listarTodo() {
        // Consultamos a la tabla 'Productos'
        $resultado = $this->db->from('Productos')->fetchAll(); 
        $this->response->result = $resultado;
        return $this->response->SetResponse(true, 'Inventario cargado');
    }

    public function crear($data) {
        $this->db->insertInto('Productos')->values([
            'nombre'     => $data->nombre,
            'stock'      => $data->stock,
            'precio'     => $data->precio,
            'id_usuario' => 1 // Valor por defecto para evitar errores de MariaDB
        ])->execute();
        return $this->response->SetResponse(true, 'Producto creado');
    }
}