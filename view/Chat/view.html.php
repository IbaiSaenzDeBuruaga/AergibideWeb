<?php
// Ensure $dataToView is available
if (!isset($dataToView)) {
    $dataToView = [];
}

$id_emisor = $_SESSION['user_data']['id'] ?? null;
$user_emisor = $_SESSION['user_data']['nombre'] ?? null; // Changed from 'username' to 'nombre'

if (!$id_emisor || !$user_emisor) {
    // Handle the case where user is not properly logged in
    echo "Error: Usuario no autenticado correctamente. ";
    if (!$id_emisor) echo "Falta ID de usuario. ";
    if (!$user_emisor) echo "Falta nombre de usuario. ";
    exit;
}
?>

<div class="chat-container">

    <div class="users-sidebar">
        <div class="sidebar-header">
            <h2>Usuarios</h2>
        </div>
        <div class="users-list">
            <?php foreach ($dataToView['usuarios'] ?? [] as $usuario): ?>
                <button class="user-item" data-user-id="<?= htmlspecialchars($usuario['id']) ?>" data-user-image="<?= htmlspecialchars($usuario['foto_perfil']) ?>">
                    <div class="user-avatar">
                        <?php if (!empty($usuario['foto_perfil'])): ?>
                            <img src="<?= htmlspecialchars($usuario['foto_perfil']) ?>" alt="<?= htmlspecialchars($usuario['username']) ?>">
                        <?php else: ?>
                            <img src="assets/img/fotoPorDefecto.png" alt="<?= htmlspecialchars($usuario['username']) ?>">
                        <?php endif; ?>
                    </div>
                    <span><?= htmlspecialchars($usuario['username']) ?></span>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="chat-area">
        <div class="chat-header">
            <div class="user-avatar">
                <img id="selected-user-avatar" src="assets/img/fotoPorDefecto.png" alt="Avatar del usuario seleccionado">
            </div>
            <h2 id="selected-user">Selecciona un usuario</h2>
        </div>
        <div id="msgBox">
            <!-- Messages will be loaded here -->
        </div>
        <div class="user-panel">
            <label for="user-combobox" class="visually-hidden"></label>
            <select id="user-combobox" name="usuarios" style="display: none;">
                <option value="">Seleccionar usuario</option>
                <?php foreach ($dataToView['usuarios'] ?? [] as $usuario): ?>
                    <option value="<?= htmlspecialchars($usuario['id']) ?>">
                        <?= htmlspecialchars($usuario['username']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="message" class="visually-hidden"></label>
            <input type="text" id="message" placeholder="Escribe tu mensaje aquí..."  />
            <button id="send-message">Enviar</button>
        </div>
    </div>
</div>

    <script>
        window.id_emisor = <?= json_encode($id_emisor) ?>;
        window.user_emisor = <?= json_encode($user_emisor) ?>;
        console.log("PHP set variables:", {
            id_emisor: window.id_emisor,
            user_emisor: window.user_emisor
        });
    </script>
<script src="assets/js/chat.js"></script>