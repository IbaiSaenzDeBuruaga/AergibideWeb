<?php
require_once "model/Pregunta.php";

class PreguntaController{
    //public $page_title;
    public $view;
    public $model;

    public function __construct(){
        //$this->page_title = "";
        $this->view = "list";
        $this -> model = new Pregunta();
    }

    public function list(){
        $pagination = 5;
        $this->view = 'list';
        $page = isset($_GET["page"]) ? $_GET["page"]:1;
        $id_tema = $_GET["id_tema"];

        $tema = $this->model->tema->getTemaById($id_tema);
        $preguntas_pag = $this->model->getPreguntasPaginated($id_tema, $pagination, $page);

        //SEPARAR preguntas_pag
        $preguntas = $preguntas_pag[0];
        $paginas = [$preguntas_pag[1], $preguntas_pag[2]];

        foreach ($preguntas as &$pregunta) {
            $usuario = $this->model->usuario->getUsuarioById($pregunta["id_usuario"]);
            $pregunta["usuario"] = $usuario;
        }
        unset($pregunta);


        return [
            "pregunta" => $preguntas,
            "tema" => $tema,
            "paginas" => $paginas
        ];
    }

    public function create(){
        $this->view = "create";

        $temas = $this->model->tema->getTemas();

        return[
            "temas" => $temas
        ];
    }

    /* Create the note */
    public function save(){
        $this->view = 'mensaje';

        // Primero, verificamos si se ha subido un archivo
        $filePath = null;
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            // Si el archivo es válido, procesarlo
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];
            $fileName = uniqid()."_".$fileName;
            $uploadFileDir = './assets/uploads/preguntas/';
            $destPath = $uploadFileDir . $fileName;

            // Verificar que el directorio de subida exista, sino crear uno
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            // Mover el archivo desde su ubicación temporal al directorio final
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Si el archivo se movió correctamente, guardar la ruta de archivo
                $filePath = $destPath;
            } else {
                // Si hubo un error al mover el archivo
                $_GET["response"] = false;
                return;
            }
        }

        // Ahora pasamos todos los datos (incluido el archivo, si existe) al modelo
        $param = $_POST;
        $param['file_path'] = $filePath;

        $id = $this->model->save($param);

        $result = $this->model->getPreguntaById($id);

        $_GET["response"] = true;
        return ["pregunta" => $result];
    }



    public function view()
    {
        $this ->view = "view";

        $id = isset($_GET["id_pregunta"]) ? $_GET["id_pregunta"] : false;
        
        if(!$id)
        {
            header("Location: index.php?controller=tema&action=mostrarTemas");
        }



        $pregunta = $this ->model->getPreguntaById($id);
        $usuarioPregunta = $this -> model -> usuario -> getUsuarioById($pregunta["id_usuario"]);

        $datos = array();

        

        $datos["pregunta"] = $pregunta;
        $datos["usuario"] = $usuarioPregunta;
  

        return $datos;

    }


    /* Like a pregunta de manera asincrona */
    public function like()
    {

        $idPregunta = $_POST["idPregunta"];
        $idUsuario = $_POST["idUsuario"];
        $meGusta = $_POST["meGusta"];

        //Primero, comprobamos si el usuario ya ha votado la pregunta
        $like = $this->model->getLikePregunta(["idPregunta" => $idPregunta, "idUsuario" => $idUsuario]);

        if($like)
        {
            //Si ya ha votado, updateamos el voto
            
            $result = $this->model->updateLikePregunta(["idPregunta" => $idPregunta, "idUsuario" => $idUsuario, "meGusta" => $meGusta]);

            if($result)
            {
                echo json_encode(["status" => "success","message" => "Votado actualizado correctamente la pregunta  "]);
                exit;
            }
            else
            {
                $result = $this->model->deleteLikePregunta(["idPregunta" => $idPregunta, "idUsuario" => $idUsuario]);
                if($result) 
                {
                    echo json_encode(["status" => "success","message" => "Voto eliminado correctamente la pregunta"]);
                    exit;
                }
                else
                {
                    echo json_encode(["status" => "error","message" => "Error al borrar el voto de la pregunta"]);
                    exit;
                }
            }
        }
        else
        {
            //Si no ha votado, insertamos el voto
            $result = $this->model->insertLikePregunta(["idPregunta" => $idPregunta, "idUsuario" => $idUsuario, "meGusta" => $meGusta]);

            if($result)
            {
                echo json_encode(["status" => "success","message" => "Votado correctamente la pregunta"]);
                exit;
            }
            else
            {
                echo json_encode(["status" => "error","message" => "Error al votar la pregunta"]);
                exit;
            }
        }

    }   

    public function guardados(){
        
        try 
        {
            $param = $_POST;

            $idPregunta = $param["idPregunta"];


            $estaGuardado = false;



            $listaGuardados = $this->model->usuario->getPreguntasSave($param["idUsuario"]);


            foreach ($listaGuardados as $guardado) {
                if($guardado["id_pregunta"] == $idPregunta)
                {
                
                    $estaGuardado = true;
                }            
            }
            
            if($estaGuardado)
            {
                
                $result = $this -> model -> deleteGuardarPregunta($param);
                if($result)
                {
                    echo json_encode(["status" => "success","message" => "delete OK"]);
                    exit;
                }
                else
                {
                    //No se le añade mensaje para que muestre el error de la base de datos
                    throw new Error();
                }


            }
            else
            {
                $result =  $this -> model -> saveGuardarPregunta($param);
                if($result)
                {
                    echo json_encode(["status" => "success","message" => "add OK"]);
                    exit;
                }
                else
                {
                    throw new Error();
                }
            
            }
        }
        catch (Error $e)
        {
           echo json_encode(["status" => "error","message" => "Ha sucedido el siguiente error -> ".$e]);
           exit();
            
        }
    }

    public function edit()
    {

        if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id_pregunta"]))
        {

            $pregunta = $this-> model -> getPreguntaById($_GET["id_pregunta"]);

            if($_SESSION["user_data"]["id"] != $pregunta["id_usuario"])
            {
                header("Location: index.php?controller=temas&action=mostrarTemas");
                exit();
            }
            else
            {
                $this-> view = "edit";
                return $pregunta;
            }



        }


    }

    public function update()
    {

        try 
        {
            if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST))
            {
                $result = $this->model->updatePregunta($_POST);
                if($result)
                {
                    header("Location: index.php?controller=respuesta&action=view&id_pregunta=".$_POST["id_pregunta"]);
                }
                else
                {   
                    throw new Error("No se ha realizado la update en la base de datos");
                }
            }   
            else
            {
                throw new Error();
            }

        } catch (Error $e) {
            header("Location: index.php");
        }


    }
   
}
