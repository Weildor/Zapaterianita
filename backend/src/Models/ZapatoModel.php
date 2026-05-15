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
            $resultado = $this->db->from('Productos')->fetchAll(); 
            $zapatos = $resultado ? array_values((array)$resultado) : [];
            return $this->response->SetResponse(true, 'Inventario cargado', $zapatos);
        } catch (\Exception $e) {
            return $this->response->SetResponse(false, 'Error al cargar zapatos: ' . $e->getMessage());
        }
    }

    public function crear($data, $id_usuario) {
        $this->db->insertInto('Productos')->values([
            'nombre'     => $data->nombre,
            'stock'      => $data->stock,
            'precio'     => $data->precio,
            'id_usuario' => $id_usuario 
        ])->execute();
        return $this->response->SetResponse(true, 'Producto creado');
    }

    public function obtenerPorId($id) {
        $resultado = $this->db->from('Productos')->where('id', $id)->fetch(); 
        if (!$resultado) {
            return $this->response->SetResponse(false, "No existe el producto con ID $id", []);
        }
        return $this->response->SetResponse(true, 'Producto encontrado', (array)$resultado);
    }

    public function actualizar($id, $data, $id_usuario) {
        $this->db->update('Productos')
            ->set([
                'nombre'     => $data->nombre,
                'stock'      => $data->stock,
                'precio'     => $data->precio,
                'id_usuario' => $id_usuario // Se actualiza con el ID del usuario activo
            ])
            ->where('id', $id)
            ->execute();

        return $this->response->SetResponse(true, "Producto ID $id actualizado correctamente");
    }

    public function eliminar($id) {
        $this->db->deleteFrom('Productos')->where('id', $id)->execute();
        return $this->response->SetResponse(true, "Producto eliminado correctamente");
    }
}