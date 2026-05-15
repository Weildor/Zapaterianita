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
        try {
            // Consultamos a la tabla 'Productos'
            $resultado = $this->db->from('Productos')->fetchAll(); 
            
            // Convertimos a array para asegurar compatibilidad con JSON
            $zapatos = $resultado ? array_values((array)$resultado) : [];

            // PASAMOS LOS DATOS COMO TERCER PARÁMETRO
            // Esto es lo que permite que se vean en Thunder Client
            return $this->response->SetResponse(true, 'Inventario cargado', $zapatos);
            
        } catch (\Exception $e) {
            return $this->response->SetResponse(false, 'Error al cargar zapatos: ' . $e->getMessage());
        }
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
    // Buscamos el producto
    $resultado = $this->db->from('Productos')->where('id', $id)->fetch(); 
    
    if (!$resultado) {
        // Si no hay resultado, mandamos array vacío como tercer parámetro
        return $this->response->SetResponse(false, "No existe el producto con ID $id", []);
    }

    // Convertimos el resultado (un solo objeto) a array
    $zapato = (array)$resultado;

    // MANDAMOS LOS DATOS COMO TERCER PARÁMETRO
    return $this->response->SetResponse(true, 'Producto encontrado', $zapato);
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