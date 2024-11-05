
<?php
if (isset($dataToView["tema"])) $tema = $dataToView["tema"];
if (isset($dataToView["pregunta"])) $preguntas = $dataToView["pregunta"];
if (isset($dataToView["paginas"])) $paginas = $dataToView["paginas"];
//if (isset($dataToView["pregunta"])) $usuario = $pregunta["usuario"] ;
//if (isset());
?>

<div class="contenedorListaPreguntas">

    <div class="listaPreguntas">
        <div class="encabezado">
            <h1><?php echo $tema["nombre"] ?></h1>
            <a href="index.php?controller=pregunta&action=create&id_tema=<?= $tema["id"] ?>" class="btn btnCrear">Crear Pregunta</a>
        </div>

        <?php if (count($preguntas) > 0): ?>
            <?php foreach ($preguntas as $pregunta): ?>
            <?php $usuario = $pregunta["usuario"]; ?>
                <div class="pregunta">
                    <p>
                        <a href="index.php?controller=respuesta&action=view&id_pregunta=<?= $pregunta["id"] ?>" class="tituloPregunta"><?= $pregunta["titulo"] ?></a>
                    </p>
                    <div class="datos-pregunta">
                        <span><?= $pregunta["votos"] ?> Votos</span>

                        <div>
                            <?= $usuario["username"] ?>
                            <?= $pregunta["fecha_hora"] ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="pagination">
                <!-- Enlaces de número de página -->
                <?php for ($i = 1; $i <= $paginas[1]; $i++): ?>
                    <a class="page-btn <?= ($i == $paginas[0]) ? 'active' : ''; ?>" href="index.php?controller=pregunta&action=list&id_tema=<?= $tema["id"] ?>&page=<?= $i; ?>">
                        <?= $i; ?>
                    </a>
                <?php endfor; ?>
            </div>

        <?php else: ?>
            <p>No hay preguntas sobre ese tema</p>
        <?php endif;?>
    </div>
</div>
