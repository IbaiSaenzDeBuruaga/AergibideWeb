
<div class="row">
    <div class="alert alert-success">

        <?php if ($_GET["action"] == "save"):
            if (isset($dataToView["pregunta"])) $pregunta = $dataToView["pregunta"]; ?>

            Pregunta realizada correctamente. <a href="index.php?controller=pregunta&action=create">Hacer otra Pregunta</a>
            <br>
            <a href="index.php?controller=pregunta&action=list&id_tema=<?= $pregunta["id_tema"] ?>">Volver al listado</a>
        <?php endif; ?>

        <?php if ($_GET["action"] == "edit"): ?>
            Pregunta editada correctamente. <a href="index.php?controller=pregunta&action=create">Hacer otra Pregunta</a>
            <br>
            <a href="index.php?listaPreguntaUsuario">Volver atras</a>
        <?php endif; ?>

        <?php if ($_GET["action"] == "delete"): ?>
            Pregunta eliminada correctamente. <a href="index.php?controller=pregunta&action=create">Hacer otra Pregunta</a>
            <br>
            <a href="index.php?listaPreguntaUsuario">Volver atras</a>
        <?php endif; ?>

    </div>
</div>