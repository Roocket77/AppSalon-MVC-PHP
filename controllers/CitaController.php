<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;

class CitaController{
    public static function index(Router $router){
        session_start();

        // debuguear($_SESSION);
        //verifica que el usuario este autenticado sino lo manda a login
        isAuth();
        $router->render('cita/index', [
            'nombre' => $_SESION['nombre'],
            'id' => $_SESION['id']

        ]);

    }
}




?>