<main class="main-content">
    <h1 class="temas-titulo">TEMAS</h1>
    <div class="containerTema">
        <div class="temas">
            <?php
            $temas = $dataToView["temas"] ?? [];
            foreach ($temas as $tema): ?>
                <div class="tema">
                    <a href="index.php?controller=pregunta&action=list&id_tema=<?= $tema['id'] ?>">
                        <?php echo htmlspecialchars($tema['nombre'] ?? ''); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="sidebar">
            <div class="ultimas-publicaciones">
                <h3>Últimas Publicaciones</h3>
                <?php
                $publicaciones = $dataToView["publicaciones"] ?? [];
                if (!empty($publicaciones)): ?>
                    <?php foreach ($publicaciones as $publicacion): ?>
                        <div class="publicacion">
                            <div class="user-avatar">
                                <?php if (!empty($publicacion['foto_perfil'])): ?>
                                    <img src="<?= htmlspecialchars($publicacion['foto_perfil']) ?>" alt="Foto de perfil">
                                <?php else: ?>
                                    <img src="assets/img/fotoPorDefecto.png" alt="Icono predeterminado">
                                <?php endif; ?>
                            </div>
                            <div class="texto">
                                <p><strong><?= htmlspecialchars($publicacion['titulo']) ?></strong></p>
                                <p><?= htmlspecialchars(implode(' ', array_slice(explode(' ', $publicacion['texto']), 0, 6))) ?>...</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay publicaciones recientes</p>
                <?php endif; ?>
            </div>

            <div class="estadisticas">
                <h3>Estadísticas</h3>
                <p>Total de publicaciones: <?= htmlspecialchars($dataToView['totalPublicaciones'] ?? 0) ?></p>
                <p>Total de usuarios: <?= htmlspecialchars($dataToView['totalUsuarios'] ?? 0) ?></p>
            </div>
        </div>
    </div>

    <!-- Paginación -->
    <div class="paginacion">
        <?php for ($i = 1; $i <= $dataToView['totalPaginas']; $i++): ?>
            <a href="?controller=tema&action=mostrarTemas&pagina=<?= $i ?>" class="page-btn <?= ($i == $dataToView['paginaActual']) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</main>