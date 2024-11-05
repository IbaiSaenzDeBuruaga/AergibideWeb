<?php
// DATOS USUARIO SESION INICIADA
$usuario = $_SESSION["user_data"];

if (isset($_GET["id_tema"])) $id_tema = $_GET["id_tema"];
if (isset($dataToView["temas"])) $temas = $dataToView["temas"];

?>

<div class="contenedorForm">
    <div class="crearPregunta">
        <h1>Crear Pregunta</h1>

        <form class="formPregunta" action="index.php?controller=pregunta&action=save" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_usuario" value="<?= $usuario["id"] ?>">

            <?php if (!isset($_GET["id_tema"])): ?>
                <div>
                    <label>
                        Tema
                        <select id="" name="id_tema">
                            <?php foreach ($temas as $tema):?>
                                <option value="<?= $tema["id"] ?>"><?= $tema["nombre"] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <br>
            <?php else: ?>
                <input type="hidden" name="id_tema" value="<?= $id_tema; ?>" />
            <?php endif; ?>

            <div>
                <label>
                    Titulo
                    <br>
                    <input type="text" name="titulo" placeholder="Titulo de tu Pregunta">
                </label>
            </div>
            <br>

            <div>
                <label>
                    Describe tu problema
                    <br>
                    <textarea name="texto" placeholder="Descripción de tu Problema"></textarea>
                </label>
            </div>
            <br>

            <div>
                <label>
                    Adjuntar Archivo
                </label>
                <br>
                <br>
                <label class="btn btnCrear">
                    Seleccionar Archivo
                    <input type="file" name="imagen" hidden="hidden">
                </label>
            </div>
            <br>
            <br>

            <div class="acciones">
                <input type="submit" value="Guardar" class="btnForm btnCrear"/>
                <a href="javascript:window.history.back()" class="btn btnCancel">Cancelar</a>
            </div>

        </form>
    </div>

</div>
