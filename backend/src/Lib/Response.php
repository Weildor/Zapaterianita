<?php
namespace Dorian\Backend\Lib;

class Response {
    public $result = [];
    public $response = false;
    public $message = '';

    public function SetResponse($response, $message = '', $data = []) {
        $this->response = $response;
        $this->message = $message;
        $this->result = $data; // <--- ¡ESTA LÍNEA ES LA CLAVE!
        
        // Retornamos el objeto completo para que el Controlador pueda convertirlo a JSON
        return $this; 
    }
}