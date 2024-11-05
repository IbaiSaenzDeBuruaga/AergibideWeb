<?php
require_once '../model/Db.php';
require_once '../model/Chat.php';

// Instancia de Db para obtener la conexión
$db = new Db();
$connection = $db->connection;

// Instancia de Chat, pasando la conexión
$chat = new Chat($connection);

// Verifica que se reciban los parámetros correctos
if (isset($_GET['id_emisor']) && isset($_GET['id_receptor'])) {
    $id_emisor = $_GET['id_emisor'];
    $id_receptor = $_GET['id_receptor'];

    // Obtiene los mensajes
    $mensajes = $chat->getMessages($id_emisor, $id_receptor);

    // Usa dataToView para enviar los mensajes a la vista
    dataToView('chat/view.html.php', ['mensajes' => $mensajes]);

} else {
    // Maneja el error en caso de falta de parámetros
    http_response_code(400);
    echo "<div>Parámetros insuficientes</div>"; // Respuesta HTML en caso de error
}
?>
