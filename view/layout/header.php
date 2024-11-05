<?php
// Incluir el archivo del controlador que maneja el modo oscuro
include 'controller/DarkModeController.php';
require_once 'model/Usuario.php';

// Comprobar si la cookie de modo oscuro está habilitada
$darkModeEnabled = isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'enabled';

// Obtener notificaciones no leídas
$usuarioModel = new Usuario();
$id_usuario = $_SESSION['user_data']['id'] ?? null; // Asegúrate de que el ID del usuario esté disponible
$notificaciones = $id_usuario ? $usuarioModel->getNotificacionesNoLeidas($id_usuario) : [];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>AERGIBIDE</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/tema.css">
    <link rel="stylesheet" href="assets/css/preguntaAcciones.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="assets/js/script.js"></script>
    <!-- Cargar los estilos de modo oscuro si la cookie está habilitada -->
    <?php if ($darkModeEnabled): ?>
        <link rel="stylesheet" href="assets/css/darkModeStyle.css"> <!-- Estilos base modo oscuro -->
    <?php endif; ?>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <!-- Comienzo Header -->
        <header class="header">
            <div class="logo" id="logoBtn">
                <img src="assets/img/LogoVectorizado.svg" alt="Logo">
            </div>
            <div class="busqueda">
                <form method="GET" action="index.php" style="display: flex; align-items: center; width: 100%;">
                    <input type="hidden" name="controller" value="Busqueda">
                    <input type="hidden" name="action" value="buscar">
                    <div class="iconos lupa">
                        <button id="btnLupa" class="iconos">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <input class="barra-busq" type="text" name="termino" placeholder="Busqueda..." value="<?= isset($_GET['termino']) ? htmlspecialchars($_GET['termino']) : '' ?>">
                    <div class="filtro">
                        <button id="btnFiltro" type="button" class="iconos" onclick="toggleFiltro()">
                            <i class="bi bi-funnel-fill"></i>
                        </button>
                    </div>
                    <div id="filtroMenu" style="display:none;">
                        <p class="filtro-titulo">Tipo de búsqueda:</p>
                        <label>
                            <input type="radio" name="filtro" value="titulo" <?= (isset($_GET['filtro']) && $_GET['filtro'] === 'titulo') ? 'checked' : '' ?>>
                            Solo en el título
                        </label>
                        <label>
                            <input type="radio" name="filtro" value="todo" <?= (isset($_GET['filtro']) && $_GET['filtro'] === 'todo') ? 'checked' : '' ?>>
                            En todo el post
                        </label>
                        <p class="filtro-titulo">Ordenar por:</p>
                        <label>
                            <label>
                                <input type="radio" name="orden" value="reciente" <?= (isset($_GET['orden']) && $_GET['orden'] === 'reciente') ? 'checked' : '' ?>> Más reciente
                            </label>
                            <label>
                                <input type="radio" name="orden" value="antiguo" <?= (isset($_GET['orden']) && $_GET['orden'] === 'antiguo') ? 'checked' : '' ?>> Más antiguo
                            </label>
                    </div>
                    <button type="submit" style="display: none;"></button>
                </form>
            </div>
            <div class="iconos panel-botones">
                <i id="chat" class="bi bi-chat-left-fill"></i>
                <div class="bell-container" onclick="toggleNotifications()">
                    <i id="bell" class="<?= count($notificaciones) > 0 ? 'bi bi-bell-fill' : 'bi bi-bell' ?>"></i>
                    <?php if (count($notificaciones) > 0): ?>
                        <span class="notification-badge"><?= count($notificaciones) ?></span>
                    <?php endif; ?>
                </div>
                <div id="notificationsDropdown" class="dropdown-content" style="display: none;">
                    <?php if (empty($notificaciones)): ?>
                        <p>No hay nuevas notificaciones</p>
                    <?php else: ?>
                        <?php foreach ($notificaciones as $notificacion): ?>
                            <form class="notificacion-form" action="index.php?controller=usuario&action=marcarNotificacionComoLeida" method="POST">
                                <input type="hidden" name="id_notificacion" value="<?php echo $notificacion['id']; ?>">
                                <input type="hidden" name="id_pregunta" value="<?php echo $notificacion['id_pregunta']; ?>">
                                <button type="submit" class="dropdown-item notificacion-item">
                                    <strong>Tienes una nueva respuesta en:</strong><br>
                                    <?php echo $notificacion['titulo_pregunta']; ?>
                                </button>
                            </form>
                        <?php endforeach; ?>
                        <button id="marcarTodasLeidasBtn" data-usuario-id="<?php echo isset($_SESSION['user_data']['id']) ? $_SESSION['user_data']['id'] : ''; ?>">Marcar todas como leídas</button>
                    <?php endif; ?>
                </div>
                <i id="person" class="bi bi-person-fill"></i>
                <!-- Menú desplegable -->
                <div id="dropdown" class="dropdown-content">
                    <a id="person1" href="index.php?controller=usuario&action=mostrarDatosUsuario">Configuración</a>
                    <a href="index.php?controller=usuario&action=cerrarSesion">Cerrar sesión</a>
                </div>
                <!-- Formulario para cambiar entre modo oscuro y claro -->
                <form method="POST" action="">
                    <button type="submit" name="toggleDarkMode" class="iconosDark">
                        <i class="bi <?= $darkModeEnabled ? 'bi-moon-stars-fill' : 'bi-moon-stars'; ?>"></i>
                    </button>
                </form>
            </div>
        </header>
        <script>
            function toggleNotifications() {
                var dropdown = document.getElementById("notificationsDropdown");
                if (dropdown.style.display === "none" || dropdown.style.display === "") {
                    dropdown.style.display = "block";
                } else {
                    dropdown.style.display = "none";
                }
            }
        </script>
        <script>
            function toggleFiltro() {
                var filtroMenu = document.getElementById("filtroMenu");
                if (filtroMenu.style.display === "none" || filtroMenu.style.display === "") {
                    filtroMenu.style.display = "block";
                } else {
                    filtroMenu.style.display = "none";
                }
            }
        </script>
        <script src="assets/js/notificaciones.js"></script>


        <!-- Fin Header -->