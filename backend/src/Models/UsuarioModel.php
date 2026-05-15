<?php
namespace Dorian\Backend\Models;

use Dorian\Backend\Lib\Response;

class UsuarioModel {
    private $db;
    private $response;

    public function __construct() {
        $mysql = new MysqlModel();
        $this->db = $mysql->sqlPDO; 
        $this->response = new Response();
    }

    public function listarTodo() {
        // Consultamos a la tabla 'Usuarios'
        $resultado = $this->db->from('Usuarios')->fetchAll(); 
        $this->response->result = $resultado;
        return $this->response->SetResponse(true, 'Lista de usuarios cargada');
    }
    // Función que no encontraba (POST)
    public function crear($data) {
        $this->db->insertInto('Usuarios')->values([
            'nombreUsuario'  => $data->nombreUsuario,
            'password'       => password_hash($data->password, PASSWORD_DEFAULT), 
            'email'          => $data->email,
            'nombreCompleto' => $data->nombreCompleto
        ])->execute();
        
        return $this->response->SetResponse(true, 'Usuario creado exitosamente');
    }
}