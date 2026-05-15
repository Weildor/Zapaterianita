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
    public function eliminar($id) {
    // Buscamos el zapato por su ID y lo borramos
    $this->db->deleteFrom('Productos')->where('id', $id)->execute();
    
    return $this->response->SetResponse(true, "Productos con ID $id eliminado correctamente");
}
public function obtenerPorId($id) {
    // Usamos .fetch() en lugar de .fetchAll() porque solo queremos uno
    $resultado = $this->db->from('Productos')->where('id', $id)->fetch(); 
    
    if (!$resultado) {
        return $this->response->SetResponse(false, "No existe el producto con ID $id");
    }

    $this->response->result = $resultado;
    return $this->response->SetResponse(true, 'Producto encontrado');
}
public function actualizar($id, $data) {
    $this->db->update('Productos')
        ->set([
            'nombre'     => $data->nombre,
            'stock'      => $data->stock,
            'precio'     => $data->precio,
            'id_usuario' => $data->id_usuario // <-- Lo agregamos aquí
        ])
        ->where('id', $id)
        ->execute();

    return $this->response->SetResponse(true, "Producto ID $id actualizado por el usuario $data->id_usuario");
}
}