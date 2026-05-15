<?php
namespace Dorian\Backend\Models;

use Dorian\Backend\Lib\Response;
use Firebase\JWT\JWT;

class UsuarioModel {
    private $db;
    private $response;
    private $secretKey = "CLAVE_MAESTRA_ZAPATERIA_2026_ABC";

    public function __construct() {
        $mysql = new MysqlModel();
        $this->db = $mysql->sqlPDO; 
        $this->response = new Response();
    }

    public function listarTodo() {
        try {
            // Obtenemos los datos de la tabla Usuarios
            $resultado = $this->db->from('Usuarios')->fetchAll(); 
            
            // Convertimos a array limpio por si la librería devuelve objetos raros
            $usuarios = $resultado ? array_values((array)$resultado) : [];

            // Pasamos los usuarios como TERCER parámetro
            return $this->response->SetResponse(true, 'Lista de usuarios cargada', $usuarios);
        } catch (\Exception $e) {
            return $this->response->SetResponse(false, 'Error al cargar usuarios: ' . $e->getMessage());
        }
    }

    public function login($email, $password) {
        $user = $this->db->from('Usuarios')
                         ->where('email', $email)
                         ->fetch();

        if ($user) {
            $user = (array)$user;
            if (password_verify($password, $user['password']) || $password == $user['password']) {
                $payload = [
                    'iat' => time(),
                    'exp' => time() + (60 * 60 * 24),
                    'data' => ['id' => $user['id'], 'email' => $user['email']]
                ];
                $jwt = JWT::encode($payload, $this->secretKey, 'HS256');

                $this->response->response = true;
                $this->response->message = "Bienvenido";
                $this->response->result = ["token" => $jwt];
                return $this->response;
            }
        }
        $this->response->response = false;
        $this->response->message = "Correo o contraseña incorrectos";
        return $this->response;
    }

    public function crear($data) {
        $data = (array)$data;
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $this->db->insertInto('Usuarios')->values([
            'nombreUsuario'  => $data['nombreUsuario'],
            'password'       => $hash,
            'email'          => $data['email'],
            'nombreCompleto' => $data['nombreCompleto']
        ])->execute();
        
        return $this->response->SetResponse(true, 'Usuario creado exitosamente');
    }
}