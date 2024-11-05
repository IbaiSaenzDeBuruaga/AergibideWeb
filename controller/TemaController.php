<?php
require_once 'model/Tema.php';
require_once 'model/Publicacion.php';
require_once 'model/Usuario.php';

class TemaController {
    public $view;
    public $model;

    public function __construct() {
        $this->model = new Tema();  // Instancia del modelo Tema
    }


    public function mostrarTemas() {
        // Definir el número de temas por página
        $temasPorPagina = 7;

        // Obtener la página actual de la URL, por defecto es la página 1
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

        // Calcular el offset para la consulta
        $offset = ($paginaActual - 1) * $temasPorPagina;

        // Obtener el total de temas para calcular el número de páginas
        $totalTemas = $this->model->contarTemas();
        $totalPaginas = ceil($totalTemas / $temasPorPagina);

        // Obtener los temas paginados
        $temas = $this->model->getTemasPaginados($temasPorPagina, $offset);

        // Obtener las últimas publicaciones
        $publicacionModel = new Publicacion();
        $publicaciones = $publicacionModel->getPubliaciones();

        $totalPublicaciones = $publicacionModel->getTotalPublicaciones();

        $usuarioModel = new Usuario();
        $totalUsuarios = $usuarioModel->getTotalUsuarios();

        // Asignar los temas, publicaciones y datos de paginación a $dataToView
        $dataToView["temas"] = $temas;
        $dataToView["publicaciones"] = $publicaciones;
        $dataToView["totalPaginas"] = $totalPaginas;
        $dataToView["paginaActual"] = $paginaActual;
        $dataToView["totalPublicaciones"] = $totalPublicaciones;
        $dataToView["totalUsuarios"] = $totalUsuarios;

        $this->view = 'view';  // Asigna la vista
        return $dataToView;  // Devuelve los datos a la vista
    }
}

