<?php

namespace Controllers;

use Model\Proyecto;
use MVC\Router;


class DashboardController {
    public static function index(Router $router) {
        iniciarSession();
        isAuth();

        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
            ]);
    }

    public static function crear_proyecto(Router $router) {
        iniciarSession();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);

            // Validación
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {
                // Generar una URL única
                $hash = md5(uniqid());
                $proyecto->url = $hash;
                // Almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];
                // Guardar el Proyecto
                $proyecto->guardar();
                // Redireccionar
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        } 

        $router->render('dashboard/crear-proyecto', [
            'alertas' => $alertas,
            'titulo' => 'Crear Proyecto'
            ]);
    }

    public static function proyecto(Router $router) {
        iniciarSession();
        isAuth();

        $token = $_GET['id'];
        if(!$token) header('Location: /dashboard');

        // Revisar que la persona que visita el  proyecto, es quien lo creo
        $proyecto = Proyecto::where('url', $token);
        if($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router) {
        iniciarSession();
        isAuth();

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil'
            ]);
    }
}