<?php
require_once 'Db.php';
class Publicacion {
    private $connection;

    public function __construct() {
        $this->getConnection();
    }

    public function getConnection() {
        $dbObj = new Db();
        $this->connection = $dbObj->connection;
    }

    //Obtener las últimas 5 publicaciones (preguntas y respuestas)
    public function getPubliaciones() {
        $sql = "
            (SELECT 'pregunta' as tipo, p.titulo, p.texto, p.fecha_hora, u.foto_perfil 
             FROM Preguntas p
             JOIN Usuarios u ON p.id_usuario = u.id)
            UNION
            (SELECT 'respuesta' as tipo, pr.titulo, r.texto, r.fecha_hora, u.foto_perfil 
             FROM Respuestas r
             JOIN Preguntas pr ON r.id_pregunta = pr.id
             JOIN Usuarios u ON r.id_usuario = u.id)
            ORDER BY fecha_hora DESC
            LIMIT 5;


        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Devolver los resultados como array asociativo
    }


    public function getPublicacionesByTemaId($tema_id) {
        $sql = "SELECT * FROM Preguntas WHERE id_tema = :tema_id";  // Filtrar preguntas por el tema
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':tema_id', $tema_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalPublicaciones() {
        $sql = "SELECT 
                (SELECT COUNT(*) FROM Preguntas) + (SELECT COUNT(*) FROM Respuestas) as total";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }


    // Buscar publicaciones (título y texto de la última respuesta) con paginación
    public function buscarPublicaciones($termino, $filtro, $orden, $resultadosPorPagina, $offset) {
        $conn = $this->connection;

        // Determinar la cláusula ORDER BY según el filtro de orden
        $orderBy = $orden === 'reciente' ? 'fecha_ultima_publicacion DESC' : 'fecha_ultima_publicacion ASC';

        // Prepara la consulta para buscar en la vista con LIMIT y OFFSET
        $stmt = $conn->prepare("
            SELECT *
            FROM vw_publicaciones
            WHERE pregunta_titulo LIKE :termino OR 
                  texto_ultima_respuesta LIKE :termino
            ORDER BY $orderBy
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':termino', '%' . $termino . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $resultadosPorPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Devuelve los resultados
    }

    // Buscar publicaciones solo en el título con paginación
    public function buscarPublicacionesPorTitulo($termino, $orden, $resultadosPorPagina, $offset) {
        $conn = $this->connection;

        // Determinar la cláusula ORDER BY según el filtro de orden
        $orderBy = $orden === 'reciente' ? 'fecha_ultima_publicacion DESC' : 'fecha_ultima_publicacion ASC';

        // Prepara la consulta para buscar solo en el título con LIMIT y OFFSET
        $stmt = $conn->prepare("
            SELECT *
            FROM vw_publicaciones
            WHERE pregunta_titulo LIKE :termino
            ORDER BY $orderBy
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':termino', '%' . $termino . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $resultadosPorPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Devuelve los resultados
    }

    public function contarPublicaciones($termino) {
        $conn = $this->connection;

        // Prepara la consulta para contar el total de resultados
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total
            FROM vw_publicaciones
            WHERE pregunta_titulo LIKE :termino OR 
                  texto_ultima_respuesta LIKE :termino
        ");
        $stmt->bindValue(':termino', '%' . $termino . '%', PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn(); // Devuelve el total
    }







}
