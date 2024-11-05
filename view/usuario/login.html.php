<?php
// Incluir el archivo del controlador que maneja el modo oscuro
include 'controller/DarkModeController.php';

// Comprobar si la cookie de modo oscuro está habilitada
$darkModeEnabled = isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'enabled';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/bodega.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>AERGIBIDE - LOGIN</title>

    <!-- Cargar los estilos de modo oscuro si la cookie está habilitada -->
    <?php if ($darkModeEnabled): ?>
        <link rel="stylesheet" href="assets/css/loginDark.css"> <!-- Estilos base modo oscuro -->
    <?php endif; ?>
</head>
<body>
<div class="containerLogo">
    <img src="assets/img/LogoVectorizado.svg" alt="Logo">
</div>

<div class="containerLogin">
    <div class="formulario">
        <form method="POST" action="">
            <button type="submit" name="toggleDarkMode" class="iconosDark">
                <i class="bi <?= $darkModeEnabled ? 'bi-moon-stars-fill' : 'bi-moon-stars'; ?>"></i>
            </button>
        </form>
        <h1><strong style="font-size: 35px; font-family: Mulish-Bold">Entrar en Aergibide</strong></h1>
        <div class="inputs">
            <div class="email">
                <label for="email"><strong>Email</strong></label><br>
                <input type="email" name="email" id="email" placeholder="Introduce tu email aquí" required><br>
            </div>

            <div class="password">
                <label for="password"><strong>Contraseña</strong></label><br>
                <input type="password" name="password" id="password" placeholder="Introduce tu contraseña aquí" required><br>
                <a href="#" onclick="mostrarMensaje()">¿Olvidaste la contraseña?</a><br>
            </div>
        </div>
        <button type="submit" id="botonDeLogin" class="boton fondoAzulFrozono">Entrar</button>
    </div>
</div>

<!-- Contenedor del mensaje emergente -->
<div id="mensajeEmergente" class="mensaje-olvidado">
    <div class="mensaje-contenido">
        <p>Si no te acuerdas de la contraseña, debes contactar con un administrador.</p>
        <button onclick="cerrarMensaje()">Cerrar</button>
    </div>
</div>

<script src="assets/js/login.js"></script>
<script>
    function mostrarMensaje() {
        // Aplica el efecto de difuminado a las clases específicas
        document.querySelector('.containerLogo').style.filter = "blur(5px)";
        document.querySelector('.containerLogin').style.filter = "blur(5px)";
        document.getElementById("mensajeEmergente").style.display = "flex";
    }

    function cerrarMensaje() {
        // Remueve el efecto de difuminado de las clases específicas
        document.querySelector('.containerLogo').style.filter = "none";
        document.querySelector('.containerLogin').style.filter = "none";
        document.getElementById("mensajeEmergente").style.display = "none";
    }
</script>
</body>
</html>
