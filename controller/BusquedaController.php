<?php
require_once 'model/Publicacion.php';

class BusquedaController {
    public $view;
    public $model;

    public function __construct() {
        $this->model = new Publicacion();  // Instancia del modelo Publicacion
    }

    public function buscar() {
        // Obtener el término de búsqueda, filtro, orden y página desde la solicitud GET
        $termino = isset($_GET['termino']) ? $_GET['termino'] : '';
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todo';  // Valor por defecto
        $orden = isset($_GET['orden']) ? $_GET['orden'] : 'reciente';  // Valor por defecto
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Página actual
        $resultadosPorPagina = 10; // Número de resultados por página
        $offset = ($pagina - 1) * $resultadosPorPagina; // Calcular el offset

        // Realizar la búsqueda en preguntas y respuestas
        if ($filtro === 'titulo') {
            $resultados = $this->model->buscarPublicacionesPorTitulo($termino, $orden, $resultadosPorPagina, $offset);
        } else {
            $resultados = $this->model->buscarPublicaciones($termino, $filtro, $orden, $resultadosPorPagina, $offset);
        }

        // Contar el total de resultados para la paginación
        $totalResultados = $this->model->contarPublicaciones($termino);

        // Asignar los resultados a $dataToView
        $dataToView["resultados"] = $resultados;
        $dataToView["termino"] = $termino;
        $dataToView["filtro"] = $filtro;  // Asegúrate de asignar esto
        $dataToView["orden"] = $orden;    // Asegúrate de asignar esto
        $dataToView["pagina"] = $pagina;
        $dataToView["totalResultados"] = $totalResultados;

        $this->view = '/view';  // Asigna la vista para mostrar los resultados
        return $dataToView;  // Devuelve los datos a la vista
    }




}
