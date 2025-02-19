<?php

//OOP DBConnect *jetzt ausgelagert*
class Database {
    private static $instance = null; //Sicherstellen nur eine Instanz
    private $conn;

    private function __construct() {
        //Parse INI to DBConf
        $file = '../app/config/dbConfig.ini';
        //Check ob richtige .ini vorhanden ist
        $config = parse_ini_file($file);
        if ($config == false || !isset($config['database'])) {
            die("Die .ini-Datei konnte nicht geladen werden. ");
        }
        
        $this->conn = new mysqli(
            $config['hostname'], 
            $config['username'], 
            $config['password'], 
            $config['database'], 
            $config['port']
        );
        //Datenbankverbindung Check
        if ($this->conn->connect_error) {
            die("Verbindung mit Datenbank fehlgeschlagen: " . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}