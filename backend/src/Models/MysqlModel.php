<?php
namespace Dorian\Backend\Models;

use Dorian\Backend\Config\Config;
use PDO;
use Envms\FluentPDO\Query;

class MysqlModel {
    public $sqlPDO;

    public function __construct() {
        $conf = Config::getStringsDB();
        
        // Cadena de conexión corregida con puerto
        $dsn = "mysql:host={$conf['local']['host']};port={$conf['local']['port']};dbname={$conf['local']['db']};charset=utf8";
        
        try {
            // Se agregaron las comas faltantes aquí:
            $pdo = new PDO($dsn, $conf['local']['user'], $conf['local']['pass']);
            
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            
            $this->sqlPDO = new Query($pdo);
            
        } catch (\PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}