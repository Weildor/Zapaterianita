<?php
namespace Dorian\Backend\Config;

class Config {
    public static function getStringsDB() {
        return [
            'local' => [
                'host' => '127.0.0.1',
                'port' => '3306',
                'db'   => 'zapaterianita',
                'user' => 'root',
                'pass' => 'Hello_world123' 
            ]
        ];
    }
}