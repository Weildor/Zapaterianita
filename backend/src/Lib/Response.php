<?php
namespace Dorian\Backend\Lib;

class Response {
    public $result = [];
    public $response = false;
    public $message = '';

    public function SetResponse($response, $message = '') {
        $this->response = $response;
        $this->message = $message;
        
        // Retornamos el objeto completo para que el Controlador pueda convertirlo a JSON
        return $this; 
    }
}