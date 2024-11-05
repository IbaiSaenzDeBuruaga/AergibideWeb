<?php
require_once 'Db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
class Chat {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection; // Asigna la conexión pasada al constructor
    }

    public function getUsuariosChat() {
        $sql = "SELECT id, username FROM Usuarios";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function enviarMensaje($emisorId, $receptorId, $mensaje) {
        $sql = "INSERT INTO Mensajes (id_emisor, id_receptor, mensaje) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$emisorId, $receptorId, $mensaje]);
    }

    public function insertarMensaje($id_emisor, $id_receptor, $mensaje) {
        // Asegúrate de que la conexión $connection esté disponible aquí
        if ($this->connection === null) {
            throw new Exception("La conexión a la base de datos no está inicializada.");
        }

        $query = "INSERT INTO Mensajes (id_emisor, id_receptor, mensaje) VALUES (:id_emisor, :id_receptor, :mensaje)";
        $stmt = $this->connection->prepare($query);

        // Ejecuta la consulta
        return $stmt->execute([
            ':id_emisor' => $id_emisor,
            ':id_receptor' => $id_receptor,
            ':mensaje' => $mensaje
        ]);
    }

    public function getMessages($id_emisor, $id_receptor) {
        // Ejemplo de consulta
        $query = "SELECT id_emisor, mensaje, fecha, emisor FROM mensajes 
              WHERE (id_emisor = ? AND id_receptor = ?) OR (id_emisor = ? AND id_receptor = ?)";

        $stmt = $this->connection->prepare($query);
        $stmt->execute([$id_emisor, $id_receptor, $id_receptor, $id_emisor]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Asegúrate de que esto solo devuelva un array

        var_dump($result); // Agrega esto para ver el resultado de la consulta
        return $result;
    }




    public function mostrarChat() {
        $usuarioModel = new Usuario();
        $usuarios = $usuarioModel->obtenerUsuarios();

        // Obtener el ID del emisor
        $id_emisor = $_SESSION['user_id'];

        // Establecer el ID del receptor por defecto (opcional)
        $id_receptor = !empty($usuarios) ? $usuarios[0]['id'] : null; // Usar el primer usuario si hay usuarios

        // Obtener mensajes para el receptor por defecto
        $chatModel = new Chat();
        $mensajes = $chatModel->getMessages($id_emisor, $id_receptor); // Obtener mensajes para el receptor por defecto

        // Preparar los datos para la vista
        $dataToView = [];
        $dataToView['usuarios'] = $usuarios;
        $dataToView['mensajes'] = $mensajes; // Incluye los mensajes en dataToView
        $dataToView['id_receptor'] = $id_receptor; // Incluye el ID del receptor por defecto

        return $dataToView;
    }

    public function obtenerMensajes($id_emisor, $id_receptor) {
        $sql = "SELECT m.mensaje, m.fecha, u.nombre AS emisor 
        FROM Mensajes m 
        JOIN Usuarios u ON m.id_emisor = u.id 
        WHERE (m.id_emisor = :id_emisor AND m.id_receptor = :id_receptor) 
           OR (m.id_emisor = :id_receptor AND m.id_receptor = :id_emisor) 
        ORDER BY m.fecha ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id_emisor' => $id_emisor, 'id_receptor' => $id_receptor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // model/Chat.php
    public function getMessagesWithEmisor($id_emisor, $id_receptor) {
        // Implementa la lógica para obtener los mensajes
        $query = "SELECT * FROM Mensajes WHERE (id_emisor = :id_emisor AND id_receptor = :id_receptor) OR (id_emisor = :id_receptor AND id_receptor = :id_emisor)";

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id_emisor', $id_emisor);
        $stmt->bindParam(':id_receptor', $id_receptor);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }








}


?>
