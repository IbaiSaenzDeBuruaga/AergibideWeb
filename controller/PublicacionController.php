<?php
require_once 'model/Publicacion.php';

class PublicacionController {
    public $view = 'view';  // Nombre de la vista que se va a cargar

    public function mostrarPublicaciones() {
        // Instanciamos el modelo de preguntas
        $modelPublicacion = new Publicacion ();
        $Publicaciones = $modelPublicacion ->getPublicaciones();  // Llamamos al método para obtener las preguntas y respuestas

        // Si ocurre algún error lo capturamos en la variable y lo pasamos a la vista
        if (isset($Publicaciones['error'])) {
            return ['error' => $Publicaciones['error']];
        }

        // Pasamos las preguntas al array dataToView
        return ['Publicaciones' => $Publicaciones];
    }

    public function mostrarEstadisticas() {
        $modelPublicacion = new Publicacion();
        $publicaciones = $modelPublicacion->getPublicaciones();
        $totalPublicaciones = $modelPublicacion->getTotalPublicaciones();

        return [
            'publicaciones' => $publicaciones,
            'totalPublicaciones' => $totalPublicaciones
        ];
    }

    public function buscarPublicaciones() {
        $modelPublicacion = new Publicacion();

        // Recuperar el término de búsqueda y el filtro seleccionado
        $termino = isset($_GET['termino']) ? $_GET['termino'] : '';
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todo';

        // Llama al método correspondiente del modelo según el filtro
        if ($filtro === 'titulo') {
            $resultados = $modelPublicacion->buscarPublicacionesPorTitulo($termino);
        } else {
            $resultados = $modelPublicacion->buscarPublicaciones($termino);
        }

        return ['resultados' => $resultados];
    }
}
