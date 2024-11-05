<?php
require_once 'model/Usuario.php';
class UsuarioController{

    public $view;
    public $model;

    public function __construct()
    {
        $this -> model = new Usuario();
    }

    public function login(){
        $this -> view = "login";
    }

    public function datosUsuario(){
        $usuarioId = $_SESSION['user_data']['id'];
        // Obtenemos los datos del usuario desde el modelo
        $usuario = $this->model->getUsuarioById($usuarioId);
        // Verificamos si el usuario está correctamente cargado
        if ($usuario) {
            echo json_encode($usuario); // Enviamos la respuesta JSON
        } else {
            echo json_encode(['error' => 'Usuario no encontrado']);
        }
        exit; // Nos aseguramos de que PHP no siga procesando después de enviar la respuesta
    }

    public function nuevoUsuario() {
        $this -> view = "nuevoUsuario";
    }

    /* 
    Metodo -> logear
    From -> Erik
    Descripción -> Esta función está pensada para que se utilice tras un Fetch desde Javascript,
    por lo tanto las respuestas que devuelve son para ser tratadas en assets/js/login.js */


    public function logear()
    {
        header('Content-Type: application/json');  // Establecer el tipo de contenido como JSON

        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) 
        {
            $row = $this->model->login($_POST);

            if (isset($row)) 
            {
                $_SESSION['is_logged_in'] = true;
                $_SESSION['user_data'] = array(
                    "id" => $row->id,
                    "nombre" => $row->nombre,
                    "email" => $row->email,
                    "username" => $row->username,
                    "foto_perfil" => $row->foto_perfil,
                    "rol" => $row->rol
                );
                //Si todo va bien y entra
                echo json_encode([
                    "status" => "success",
                    "message" => "Login exitoso",
                    "redirect" => "index.php?controller=tema&action=mostrarTemas"
                ]);
                exit();
            } 
            else 
            {
                //Si el 
                echo json_encode([
                    "status" => "error",
                    "message" => "Usuario o password no válido"
                ]);
                exit();
            }
        } 
        elseif(isset($_SESSION["is_logged_in"]) && isset($_SESSION["user_data"]["id"]))
        {
            echo json_encode([
                "status" => "success",
                "message" => "Usuario con sesión iniaciada",
                "redirect" => "index.php?controller=tema&action=mostrarTemas"
            ]);
            exit();
        }
        else 
        {
            echo json_encode([
                "status" => "error",
                "message" => "No ha entrado en la condición de la sesión",
                "datosDeSesion" => $_SESSION["user_data"]["id"]." is loged"
            ]);
            exit();
        }
    }

    public function apiRegistrar()
    {
        $this -> view = "login";
        isset($_POST) ? $this -> model -> insertUsuario($_POST) : print_r("error");
        echo json_encode([
            "status" => "success",
            "message" => "Usuario creado correctamente"
        ]);
        exit();
    }

    public function mostrarDatosUsuario() {
        $this -> view = "datosUsuario"; 
    }

    public function mostrarGestionUsuario() {
        // Llamo a la vista html.php
        $this -> view = "gestionUsuario";
        // Recogo los datos en la variable $users
        $users = $this->model->getUsers();
        include __DIR__ . '/../view/layout/header.php';
        // Incluyo la vista para añadir los datos
        include __DIR__ . '/../view/usuario/gestionUsuario.html.php';
    }

    public function obtenerTotalUsuarios() {
        $totalUsuarios = $this->model->getTotalUsuarios();
        return ["totalUsuarios" => $totalUsuarios];
    }


    public function update() {
        if (isset($_POST)) {
            // Guardamos el id de la sesión
            $usuarioId = $_SESSION['user_data']['id'];
            // Mediante el id obtenemos el usuario y lo guardamos
            $usuario = $this->model->getUsuarioByIdObj($usuarioId);
            
            // Guardamos los campos editados
            $usuario->nombre = $_POST['nombre'];
            $usuario->apellido = $_POST['apellido'];
            $usuario->username = $_POST['username'];
            $usuario->email = $_POST['email'];

            // Verificar contraseña
            $usuarioAlmacenado = $this->model->getUsuarioByEmail($_POST['email']);
            if (password_verify($_POST["actualPassword"], $usuarioAlmacenado->password)) {
                $usuario->password = password_hash($_POST['nuevaPassword'], PASSWORD_BCRYPT);
            } else {
                echo "La contraseña actual es incorrecta.";
            }
    
            // Actualizar usuario
            $this->model->updateUsuario($usuario);
            header("Location: index.php?controller=usuario&action=mostrarDatosUsuario");
            exit();
        }
    }

    public function create() {
        if (isset($_POST)) {
            $usuario = new stdClass();
            $usuario->nombre = $_POST['nombre'];
            $usuario->apellido = $_POST['apellido'];
            $usuario->username = $_POST['username'];
            $usuario->email = $_POST['email'];
    
            // Capturamos el rol
            $usuario->rol = $_POST['rol']; // Asegúrate de tener un campo "rol" en tu formulario
    
            // Confirmar contraseña
            if ($_POST["nuevaPassword"] === $_POST["repetirPassword"]) {
                $usuario->password = password_hash($_POST['nuevaPassword'], PASSWORD_BCRYPT);
            } else {
                echo "Las contraseñas no coinciden.";
                return;
            }
    
            // Crear usuario
            $this->model->createUsuario($usuario);
            header("Location: index.php?controller=usuario&action=mostrarDatosUsuario");
            exit();
        }
    }

    public function updateFoto() {

        if (isset($_POST)) {
            // Guardamos el id de la sesión
            $usuarioId = $_SESSION['user_data']['id'];
            // Mediante el id obtenemos el usuario y lo guardamos
            $usuario = $this->model->getUsuarioByIdObj($usuarioId);

            if (isset($_FILES['nuevaFoto']) && $_FILES['nuevaFoto']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['nuevaFoto']['tmp_name'];
                $fileMimeType = mime_content_type($fileTmpPath);
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];

                if (in_array($fileMimeType, $allowedMimeTypes)) {
                    $fileName = uniqid() . '-' . $_FILES['nuevaFoto']['name'];
                    $uploadFileDir = 'assets/img/';
                    $destPath = $uploadFileDir . $fileName;

                    // Movemos el archivo a la carpeta deseada
                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $usuario->foto_perfil = $destPath; // Asignamos la nueva ruta a la foto
                    } else {
                        echo "No se pudo subir la imagen.";
                        return;
                    }
                } else {
                    echo "Tipo de archivo no permitido.";
                    return;
                }

                $this->model->updateUsuario($usuario);
                header("Location: index.php?controller=usuario&action=mostrarDatosUsuario");
                exit();
            }
        }
    }

    public function cerrarSesion() {
        // Aquí destruyes la sesión y rediriges al usuario
        session_start(); // Asegúrate de que la sesión esté iniciada
        session_unset(); // Limpia todas las variables de sesión
        session_destroy(); // Destruye la sesión

        // Redirige al usuario a la página de inicio de sesión
        header("Location: index.php?controller=usuario&action=login");
        exit();
    }


    public function marcarNotificacionComoLeida()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_notificacion']) && isset($_POST['id_pregunta'])) {
            $id_notificacion = $_POST['id_notificacion'];
            $id_pregunta = $_POST['id_pregunta'];

            // Marcar la notificación como leída
            $this->model->marcarNotificacionComoLeida($id_notificacion);

            // Redirigir a la página de la pregunta
            header("Location: index.php?controller=respuesta&action=view&id_pregunta=" . $id_pregunta);
            exit;
        } else {
            // Si no se recibieron los datos esperados, redirigir a la página principal
            header("Location: index.php");
            exit;
        }
    }

    public function marcarTodasNotificacionesComoLeidas() {
        if (isset($_GET['id_usuario'])) {
            $id_usuario = $_GET['id_usuario'];
            $result = $this->model->marcarTodasNotificacionesComoLeidas($id_usuario);
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false, 'error' => 'ID de usuario no proporcionado']);
        }
        exit;
    }

}