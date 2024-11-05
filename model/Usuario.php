<?php
class Usuario{

    private $tabla = "Usuarios";
    private $connection;
    public $id;
    public $nombre;
    public $email;
    public $username;
    public $foto_perfil;


    public function __construct()
    {
        $this -> getConnection();
    }

    public function getConnection(){
        $dbObj = new db();
        $this -> connection = $dbObj ->connection;
    }

    public function getUsuarioById($id_usuario)
    {
        $sql = "SELECT * FROM ".$this->tabla. " WHERE id=?";
        $stmt = $this -> connection ->prepare($sql);
        //$stmt ->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
        $stmt->execute([$id_usuario]);
        return $stmt ->fetch();
    }

    public function getUsuarioByIdObj($id_usuario)
    {
        $sql = "SELECT * FROM ".$this->tabla. " WHERE id=?";
        $stmt = $this -> connection ->prepare($sql);
        //$stmt ->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
        $stmt->execute([$id_usuario]);
        return $stmt ->fetch(PDO::FETCH_OBJ);
    }   

    public function getAllUsuarios() {
        $stmt = $this->connection->prepare("SELECT * FROM Usuarios");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function insertUsuario($param)
    {

        // Sanitize POST
       // $param = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            /* Validación del token para prevenir inyección SQL */
     /*   $listaSQL = ["WHERE", "where", "AND", "and"];
        $limpio = true; // Cambié a true para evitar invertir la lógica después
        foreach($listaSQL as $strSQL) {
            foreach($post as $elementoPost)
            {
                if (strpos($elementoPost, $strSQL) !== false) {
                    echo "Es falso";
                    $limpio = false;
                    break;
                }
            }

        }
        */

        $post = $param;
        if (isset($post) )
        {

            if ($post['email'] == '' ||
            $post['password'] == '') {
            return;
            }

            $passwordHaseada = password_hash($post["password"], PASSWORD_DEFAULT);

            $stmt = $this -> connection -> prepare("INSERT INTO ".$this->tabla." ( email,
            nombre, password, username, rol, foto_perfil, apellido) VALUES (:email, :nombre, :password,
            :username, :rol, :foto_perfil , :apellido)");

            $stmt -> execute([
                ':email' => isset($post['email']) ? $post['email'] : "",
                ':nombre' => isset($post["nombre"]) ? $post["nombre"] : "",
                ':password' => isset($passwordHaseada) ? $passwordHaseada : "",
                ':username' => isset($post['username']) ? $post['username'] : "",
                ':rol' => isset($post['rol']) ? $post['rol'] : "",
                ':foto_perfil' => isset($post['foto_perfil']) ? $post['foto_perfil'] : "",
                ':apellido' => isset($post['apellido']) ? $post['apellido'] : "",
            ]);

            return;



        }


    }

    public function getUsuarios(){

        $sql = "SELECT * FROM ".$this->tabla;
        $stmt = $this -> connection ->prepare($sql);
        /*Esta linéa del FetchMode lo que hace es convertilo en objetos Usuario donde las columnas de la tabla
        son los atributos del mismo */
        $stmt ->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
        $stmt->execute();
        return $stmt ->fetchAll();
    }

    public function getUsuariosChat()
    {
        $sql = "SELECT id, username, foto_perfil FROM Usuarios";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        public function getUsuarioByEmail($email)
    {
        $sql = "SELECT * FROM ".$this->tabla." WHERE email = ?";
        $stmt = $this-> connection ->prepare($sql);
        $stmt -> setFetchMode(PDO::FETCH_CLASS, 'Usuario');
        $stmt -> execute([$email]);
        return $stmt ->fetch();
    }

    public function login($param)
    {
        // Sanitize POST

       // $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            /* Validación del token para prevenir inyección SQL */
      /*  $listaSQL = ["WHERE", "where", "AND", "and", "="];
        $limpio = true; // Cambié a true para evitar invertir la lógica después
        foreach($listaSQL as $strSQL) {
            foreach($post as $elementoPost)
            {
                if (strpos($elementoPost, $strSQL) !== false) {
                    $limpio = false;
                    break 2; // Salimos de ambos bucles
                }
            }
        } */

        //if (isset($post['submit']) && $limpio)

        $post = $param;


        if (isset($post))
        {
            $usuarioAlmacenado = $this->getUsuarioByEmail($post['email']);


            if(isset($usuarioAlmacenado->email) && password_verify($post["password"] , $usuarioAlmacenado->password))
            {

                return $usuarioAlmacenado;
            }
            else
                return;

        }

    }

    public function getTotalUsuarios() {
        $sql = "SELECT COUNT(*) as total FROM Usuarios";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function updateUsuario($objeto) {
        $sql = "UPDATE " . $this->tabla .
               " SET nombre = :nombre, apellido = :apellido, username = :username, email = :email, password = :password, foto_perfil = :foto_perfil WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $objeto->id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $objeto->nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellido', $objeto->apellido, PDO::PARAM_STR);
        $stmt->bindParam(':username', $objeto->username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $objeto->email, PDO::PARAM_STR);

        $passwordHaseada = password_hash($objeto->password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $passwordHaseada, PDO::PARAM_STR);
        $stmt->bindParam(':foto_perfil', $objeto->foto_perfil, PDO::PARAM_STR);
    
        if ($stmt->execute()) {
            echo "Foto guardada: " . $objeto->foto_perfil;
        } else {
            echo "Error al actualizar la foto";
        }
    }

    public function createUsuario($objeto) {
        $sql = "INSERT INTO " . $this->tabla . " (nombre, apellido, username, email, password, foto_perfil, rol) 
                VALUES (:nombre, :apellido, :username, :email, :password, :foto_perfil, :rol)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':nombre', $objeto->nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellido', $objeto->apellido, PDO::PARAM_STR);
        $stmt->bindParam(':username', $objeto->username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $objeto->email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $objeto->password, PDO::PARAM_STR);
        $stmt->bindParam(':foto_perfil', $objeto->foto_perfil, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $objeto->rol, PDO::PARAM_STR); // Agrega esta línea
    
        if ($stmt->execute()) {
            echo "Usuario creado exitosamente.";
        } else {
            echo "Error al crear el usuario.";
        }
    }
    
    public function getUsers() {
        $sql = "SELECT username FROM " . $this->tabla . " WHERE rol = 'user'";
        $stmt = $this -> connection ->prepare($sql);
        $stmt ->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
        $stmt->execute();
        return $stmt ->fetchAll(PDO::FETCH_OBJ);
    }

    public function getPreguntasSave($idUsuario){
        $sql = "SELECT * FROM Preguntas_Usu_Save WHERE id_usuario=?";
        $stmt = $this -> connection ->prepare($sql);
        $stmt->execute([$idUsuario]);
        return $stmt ->fetchAll();
    }

    public function getRespuestasSave($idUsuario){
        $sql = "SELECT * FROM Respuestas_Usu_Save WHERE id_usuario=?";
        $stmt = $this -> connection ->prepare($sql);
        $stmt->execute([$idUsuario]);
        return $stmt ->fetchAll();
    }


    public function getPreguntasLike($idUsuario)
    {
        $sql = "SELECT * FROM Preguntas_Usu_Like WHERE id_usuario = ?";
        $stmt = $this -> connection -> prepare($sql);
        $stmt-> execute([$idUsuario]);
        return $stmt -> fetchAll();
    }

    public function getRespuestasLike($idUsuario)
    {
        
        $sql = "SELECT * FROM Respuestas_Usu_Like WHERE id_usuario = ?";
        $stmt = $this -> connection -> prepare($sql);
        $stmt-> execute([$idUsuario]);
        return $stmt -> fetchAll();
    }

    public function ObtenerUsuarios() {
        $sql = "SELECT id, nombre FROM " . $this->tabla; // Aquí seleccionamos solo 'id' y 'nombre'
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna el resultado como array asociativo
    }

    public function getNotificacionesNoLeidas($id_usuario) {
        try {
            $sql = "SELECT n.*, p.titulo AS titulo_pregunta 
                FROM Notificaciones n 
                LEFT JOIN Preguntas p ON n.id_pregunta = p.id 
                WHERE n.id_usuario = :id_usuario AND n.leido = 0";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([':id_usuario' => $id_usuario]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log the error
            error_log("Error en getNotificacionesNoLeidas: " . $e->getMessage());

            // Re-lanzar la excepción para que pueda ser manejada en un nivel superior si es necesario
            throw $e;
        }
    }

    public function marcarNotificacionComoLeida($id_notificacion) {
        $sql = "UPDATE Notificaciones SET leido = 1 WHERE id = :id_notificacion";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id_notificacion' => $id_notificacion]);
    }

    public function marcarTodasNotificacionesComoLeidas($id_usuario) {
        $sql = "UPDATE Notificaciones SET leido = 1 WHERE id_usuario = :id_usuario AND leido = 0";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([':id_usuario' => $id_usuario]);
    }

}