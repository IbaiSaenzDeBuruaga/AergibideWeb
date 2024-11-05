<?php
// Asegúrate de incluir tu clase Db y Chat
require_once '../model/Db.php';
require_once '../model/Chat.php';

// Inicializa la conexión a la base de datos
$db = new Db();
$conexion = $db->connection; // Obtén la conexión

// Instancia la clase Chat con la conexión
$chat = new Chat($conexion);

// Obtiene los datos del mensaje desde POST
$id_emisor = $_POST['id_emisor'];
$id_receptor = $_POST['id_receptor'];
$mensaje = $_POST['mensaje'];

// Intenta insertar el mensaje
try {
    $resultado = $chat->insertarMensaje($id_emisor, $id_receptor, $mensaje);
    echo $resultado ? "Mensaje insertado correctamente" : "Error al insertar el mensaje";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
