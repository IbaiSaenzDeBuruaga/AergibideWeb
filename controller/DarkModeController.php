<?php
// Verificar si se hizo un POST para cambiar el modo oscuro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggleDarkMode'])) {
    // Si la cookie está habilitada, deshabilitarla; si está deshabilitada, habilitarla
    if (isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'enabled') {
        setcookie('darkMode', 'disabled', time() + (7 * 24 * 60 * 60), '/'); // Deshabilitar por 7 días
    } else {
        setcookie('darkMode', 'enabled', time() + (7 * 24 * 60 * 60), '/'); // Habilitar por 7 días
    }

    // Redirigir a la página anterior
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php')); // Redirige a la página anterior o a index.php si no hay referencia
    exit();
}
?>
