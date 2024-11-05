<?php
require_once __DIR__ . '/../config/config.php';

class Db {
    private $host;
    private $db;
    private $user;
    private $pass;
    public $connection;  // Asegúrate de que la propiedad es 'connection'

    public function __construct() {
        $this->host = constant('DB_HOST');
        $this->db = constant('DB');
        $this->user = constant('DB_USER');
        $this->pass = constant('DB_PASS');
        try {
            // Asegúrate de que la conexión esté bien definida
            $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db, $this->user, $this->pass);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit();
        }
    }
}
