<?php
require_once 'model/Chat.php';
require_once 'model/Usuario.php';
require_once 'model/Db.php';

class ChatController
{
    private $usuarioModel;
    private $chatModel;
    public $view = "view";

    public function __construct()
    {
        $this->usuarioModel = new Usuario(); // Modelo Usuario
        $db = new Db(); // Crear instancia de Db
        $this->chatModel = new Chat($db->connection);
        // Pasar la conexión al modelo Chat
    }

    public function mostrarChat() {
        if (!isset($_SESSION['user_data']['id'])) {
            return ['error' => 'Usuario no autenticado'];
        }

        $usuarios = $this->usuarioModel->getUsuariosChat();

        $dataToView = [
            'usuarios' => $usuarios,
        ];

        return $dataToView;
    }


    public function get_messages() {
        $id_emisor = $_GET['id_emisor'] ?? null;
        $id_receptor = $_GET['id_receptor'] ?? null;

        if ($id_emisor === null || $id_receptor === null) {
            echo json_encode(['error' => 'ID de emisor o receptor no proporcionado.']);
            exit();
        }

        // Establecer la zona horaria
        date_default_timezone_set('Europe/Madrid');

        $mensajes = $this->chatModel->obtenerMensajes($id_emisor, $id_receptor);

        // Formatear la fecha y hora de cada mensaje
        foreach ($mensajes as &$mensaje) {
            $fecha = new DateTime($mensaje['fecha']);
            $mensaje['fecha_formateada'] = $fecha->format('Y-m-d H:i:s');
        }

        // Verificar si hay mensajes
        if (empty($mensajes)) {
            echo json_encode(['mensaje' => 'No hay mensajes aún.']);
        } else {
            echo json_encode($mensajes);
        }
        exit();
    }





}
