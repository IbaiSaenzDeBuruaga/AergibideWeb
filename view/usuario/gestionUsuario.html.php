<div class="containerPerfil">

    <?php
        require_once "panelLateral.html.php";
    ?>

    <div class="containerPanel">
        <div class="botonesArriba">
            <a href="index.php?controller=usuario&action=mostrarGestionUsuario" class="lateralBoton <?php echo ($currentController === 'usuario' && $currentAction === 'mostrarGestionUsuario') ? 'active' : ''; ?>">
                Gestión Usuarios
            </a>
            <a href="index.php?controller=usuario&action=nuevoUsuario" class="lateralBoton <?php echo ($currentController === 'usuario' && $currentAction === 'nuevoUsuario') ? 'active' : ''; ?>">
                Nuevo Usuario
            </a>
        </div>

        <div class="listaUsers">
            <?php if (isset($users) && count($users) > 0): ?>
            <ul>
                <?php foreach ($users as $user): ?>
                    <li><?php echo $user->username; ?></li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
                <p>No hay usuarios disponibles.</p>
            <?php endif; ?>
        </div>
    </div>
</div>