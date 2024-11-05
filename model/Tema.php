<?php
class Tema {
    private $table = "Temas";
    private $connection;

    public function __construct() {
        $this->getConnection();
    }

    public function getConnection() {
        $dbObj = new Db();
        $this->connection = $dbObj->connection;
    }

    // Método para contar el total de temas
    public function contarTemas() {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Método para obtener temas paginados
    public function getTemasPaginados($limite, $offset) {
        $sql = "SELECT * FROM " . $this->table . " LIMIT :limite OFFSET :offset";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTemaById($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }


    public function getTemas()
    {
        $sql =  "SELECT * FROM ".$this->table;
        $stmt = $this->connection->prepare($sql);
        $stmt -> execute();
        return $stmt->fetchAll();
    }


}
