<?php


    $pregunta = $dataToView["pregunta"]["datosPregunta"];
    $usuarioPregunta = $dataToView["pregunta"]["usuarioPregunta"];
    $respuestas = $dataToView["respuestas"];



    $respuestasGuardadas = $dataToView["guardados"]["respuestasGuardadas"];
    $preguntasGuardadas = $dataToView["guardados"]["preguntasGuardadas"];

    
    $respuestasLike = $dataToView["likes"]["respuestasLikes"];
    $preguntasLike = $dataToView["likes"]["preguntasLikes"];

    function verificarSiPreguntaGuardada($idPregunta, $preguntasGuardadas)
    {
        $estaGuardado = false;
        
        foreach ($preguntasGuardadas as $objPregunta) {

            if($objPregunta["id_pregunta"] == $idPregunta){ return $estaGuardado = true;}
        }

        return $estaGuardado;
    }

    function verificarSiRespuestaGuardada($idRespuesta,$respuestasGuardadas)
    {
        $estaGuardado = false;
        
        foreach ($respuestasGuardadas as $objRespuesta) {

            if($objRespuesta["id_respuesta"] == $idRespuesta){ return $estaGuardado = true;}
        }

        return $estaGuardado;
    }


    function verificarSiPreguntaLike($idPregunta,$preguntasLike)
    {
        $isLike = false;

        foreach($preguntasLike as $objPregunta)
        {
            if($objPregunta["id_pregunta"] == $idPregunta)
            {
                if($objPregunta["me_gusta"])
                {
                    return "esLike";
                }
                else
                {
                    return "esDisLike";
                }
            }
        }
        return $isLike;
        
    }
    
    function verificarSiRespuestaLike($idRespuesta,$respuestasLike)
    {
        $isLike = false;

        foreach($respuestasLike as $objRespuesta)
        {
            if($objRespuesta["id_respuesta"] == $idRespuesta)
            {
                if($objRespuesta["me_gusta"])
                {
                    return "esLike";
                }
                else
                {
                    return "esDisLike";
                }
            }
        }
        return $isLike;
        
    }


?>

<input type="text" id="userId" value="<?php echo $_SESSION["user_data"]['id']; ?>" hidden >
<div class="contenedorPreguntasYRespuestas">
    <div class="contenedorPregunta">
        <div class="fotoUsuarioPregunta">
            <?php

                $fotoUsuarioPorDefecto = "assets/img/fotoPorDefecto.png";
               
                
            ?>
            <img src="<?php echo file_exists($usuarioPregunta["foto_perfil"]) ? $usuarioPregunta["foto_perfil"] : $fotoUsuarioPorDefecto;?>" alt="Foto de usuario">
        </div>

        <div class="preguntaTitulo">
           <p><?php echo isset($pregunta["titulo"]) ? $pregunta["titulo"] : "Titulo no encontrado";?></p>
           <label id="editarPregunta" class="botonDeEditar" <?php if($usuarioPregunta["id"] != $_SESSION["user_data"]["id"]){echo "hidden";}?>>
            <a href="index.php?controller=pregunta&action=edit&id_pregunta=<?php echo $pregunta["id"];?>"><i class="bi bi-pencil-square"></i></a></label>
            <label id="eliminarPregunta" class="botonDeEditar" <?php if($usuarioPregunta["id"] != $_SESSION["user_data"]["id"]){echo "hidden";}?>>            <a href="index.php?controller=pregunta&action=edit&id_pregunta=<?php echo $pregunta["id"];?>"><i class="bi bi-pencil-square"></i></a></label>
        </div>
    

        <div class="descripcionPregunta">
            <?php echo isset($pregunta["texto"]) && $pregunta["texto"] != null ? $pregunta["texto"] : "";?>
        </div>
        <div class="panelDeBotones">
            <?php
                $like = verificarSiPreguntaLike($pregunta["id"],$preguntasLike);
                if(!$like)
                {
                    ?>
                      <button class="botonPanel" id="botonPreguntaLike" value="<?php echo $pregunta["id"];?>">
                            <i class="bi bi-airplane"></i>
                        </button>
                        <p>
                            <?php //Cuando este la view de BD que recoja los likes meterlo aquí 
                            echo 0;?>
                        </p>
                        <button class="botonPanel" id="botonPreguntaDislike" value="<?php echo $pregunta["id"];?>">
                            <i class="bi bi-airplane airplane-down"></i>
                        </button>
                <?php
                }
                elseif($like == "esLike")
                {
                    ?>
                    <button class="botonPanel" id="botonPreguntaLike" value="<?php echo $pregunta["id"];?>">
                          <i class="bi bi-airplane-fill"></i>
                      </button>
                      <p>
                          <?php //Cuando este la view de BD que recoja los likes meterlo aquí 
                          echo 0;?>
                      </p>
                      <button class="botonPanel" id="botonPreguntaDislike" value="<?php echo $pregunta["id"];?>">
                          <i class="bi bi-airplane airplane-down"></i>
                      </button>
                  <?php
                }
                else
                {
                    ?>
                    <button class="botonPanel" id="botonPreguntaLike" value="<?php echo $pregunta["id"];?>">
                          <i class="bi bi-airplane"></i>
                      </button>
                      <p>
                          <?php //Cuando este la view de BD que recoja los likes meterlo aquí 
                          echo 0;?>
                      </p>
                      <button class="botonPanel" id="botonPreguntaDislike" value="<?php echo $pregunta["id"];?>">
                          <i class="bi bi-airplane-fill airplane-down"></i>
                      </button>
                  <?php
                }    
            
            ?>
            <button class="botonPanel" id="botonGuardarPregunta" value="<?php echo $pregunta["id"];?>">
                <?php
                    if(verificarSiPreguntaGuardada($pregunta["id"],$preguntasGuardadas))
                    {
                        ?><i class="bi bi-bookmark-fill"></i>
                    <?php
                    }
                    else
                    {?>
                        <i class="bi bi-bookmark"></i>
                    <?php
                    }
                ?>
                
            </button>
        </div>
    </div>
    
    <!--Aqui comienzan las respuestas-->



    
    <div class="contenedorRespuesta">
    <?php

    if(count($respuestas["datosRespuestas"]) > 0)
    {
        for ($i=0; $i < count($respuestas["datosRespuestas"]); $i++) { 
            $usuarioRespuesta = $respuestas["usuariosRespuestas"][$i];
            $datosRespuesta = $respuestas["datosRespuestas"][$i];
    ?>
            <div class="contenedorRespuestaDivididor">
                <div class="fotoUsuarioRespuesta">
                    <img src="<?php echo file_exists($usuarioRespuesta["foto_perfil"]) ? $usuarioRespuesta["foto_perfil"] : $fotoUsuarioPorDefecto;?>" alt="Foto de usuario">
                </div>
                <div class="respuesta">
                    <div class="estrella-respuesta">
                        <label id="editarRespuesta-<?php echo $datosRespuesta["id"];?>" value = "<?php echo $datosRespuesta["id"];?>" data-id-pregunta="<?php echo $pregunta["id"];?>" class="botonDeEditar" 
                        <?php if($datosRespuesta["id_usuario"] != $_SESSION["user_data"]["id"]){echo "hidden";}?>>
                            <i class="bi bi-pencil-square botonEditar"></i>
                        </label>
                        <i class="bi bi-star"></i>
                    </div>
                    <div class="contenidoRespuesta">
                        <?php echo $datosRespuesta["texto"]; ?>
                        <?php if($datosRespuesta["imagen"] != null){?>
                            <img class="imagenRespuesta" src="<?php echo $datosRespuesta["imagen"];?>" alt="Imagen de respuesta">
                        <?php }?>
                    </div>

                </div>
                <div class="panelDeBotones">
                <?php
                $like = verificarSiRespuestaLike($datosRespuesta["id"],$respuestasLike);
                if(!$like)
                {
                    ?>
                    <button class="botonPanel" id="botonRespuestaLike-<?php echo $datosRespuesta["id"];?>" value="<?php echo $datosRespuesta["id"];?>">
                            <i class="bi bi-airplane"></i>
                        </button>
                        <p>
                            <?php //Cuando este la view de BD que recoja los likes meterlo aquí 
                            echo 0;?>
                        </p>
                        <button class="botonPanel" id="botonRespuestaDisLike-<?php echo $datosRespuesta["id"];?>" value="<?php echo $datosRespuesta["id"];?>">
                            <i class="bi bi-airplane airplane-down"></i>
                        </button>
                <?php
                }
                elseif($like == "esLike")
                {
                    ?>
                    <button class="botonPanel" id="botonRespuestaLike-<?php echo $datosRespuesta["id"];?>   " value="<?php echo $datosRespuesta["id"];?>">
                          <i class="bi bi-airplane-fill"></i>
                      </button>
                      <p>
                          <?php //Cuando este la view de BD que recoja los likes meterlo aquí 
                          echo 0;?>
                      </p>
                      <button class="botonPanel" id="botonRespuestaDisLike-<?php echo $datosRespuesta["id"];?>" value="<?php echo $datosRespuesta["id"];?>">
                          <i class="bi bi-airplane airplane-down"></i>
                      </button>
                  <?php
                }
                else
                {
                    ?>
                    <button class="botonPanel" id="botonRespuestaLike-<?php echo $datosRespuesta["id"];?>" value="<?php echo $datosRespuesta["id"];?>">
                          <i class="bi bi-airplane"></i>
                      </button>
                      <p>
                          <?php //Cuando este la view de BD que recoja los likes meterlo aquí 
                          echo 0;?>
                      </p>
                      <button class="botonPanel" id="botonRespuestaDisLike-<?php echo $datosRespuesta["id"];?>" value="<?php echo $datosRespuesta["id"];?>">
                          <i class="bi bi-airplane-fill airplane-down"></i>
                      </button>
                  <?php
                }    
            
            ?>
                    <button class="botonPanel" id="botonGuardarRespuesta-"<?php echo $datosRespuesta["id"];?> value="<?php echo $datosRespuesta["id"]?>">
                    <?php
                        if(verificarSiRespuestaGuardada($datosRespuesta["id"],$respuestasGuardadas))
                        {
                            ?><i class="bi bi-bookmark-fill"></i>
                        <?php
                        }
                        else
                        {?>
                            <i class="bi bi-bookmark"></i>
                        <?php
                        }
                    ?>
                    </button>
                </div>
            </div>
    <?php

        }
    }
    

    ?>
    </div>
    <div class="publicarRespuesta">
        <form action="index.php?controller=respuesta&action=create&id_pregunta=<?php echo $pregunta["id"];?>" method="post" enctype="multipart/form-data">
        <div class="contenedorRespuestaDivididor">
                <div class="fotoUsuarioRespuesta">
                    <img src="<?php echo file_exists($_SESSION["user_data"]["foto_perfil"]) ? $_SESSION["user_data"]["foto_perfil"] : $fotoUsuarioPorDefecto;?>" alt="Foto de usuario">
                </div>
                <div class="publicarRespuestaContenido">
                    <textarea class="textAreaRespuesta"name="texto" id=""></textarea>
                    <label class="botonSubirArchivo">
                        Subir Archivo
                        <input type="file" name="imagen" id="cargadorDeImagenRespuesta" accept="image/*" hidden>
                        <label id="archivoSubidoRespuesta" hidden>
                            <i class="bi bi-check-circle-fill"></i>
                        </label>
                    </label>
                    
                </div>
                <div class="panelDeBotones">
                    <button class="botonPanel" type="submit">
                        <i class="bi bi-send"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
<script src="assets/js/respuestas.js"></script>

</div>