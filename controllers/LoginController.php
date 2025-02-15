<?php



namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Clases\Email;
use Model\ActiveRecord;



class LoginController {
    public static function login (Router $router) {

        $alertas= [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //comprobar si el usuario existe
                $usuario = Usuario::whereUsuario('email', $auth->email);
                
                if($usuario){
                    //Verificar el password

                    if($usuario-> comprobarPasswordAndVerificado($auth->password)){
                        //authenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //REDICCIONAMIENTO

                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;

                            header('Location: /admin');
                        } else{
                            header('Location: /cita');
                        }

                        debuguear($_SESSION);


                    }
                }else{
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
            // 
            // debuguear($auth);


        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth-> validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::whereUsuario('email', $auth->email);

                if($usuario && $usuario->confirmado = "1"){

                    //Generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    // Enviar el  email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();



                    //alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                    
                } else{
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                    

                }
                $alertas = Usuario::getAlertas();
            }


        }

        $router->render('auth/olvide-password', [

            'alertas' => $alertas


        ]);
    }
    public static function recuperar(Router $router) {

        $alertas = [];
        $token = s($_GET['token']);
        $error = false;

        
        //buscar usuario por su token
        $usuario = Usuario::whereUsuario('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] ==='POST'){
            // Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            
            if(empty($alertas)){
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }

            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [

            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {

        $usuario = new Usuario;

        //Alertas Vacias
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $usuario -> sincronizar($_POST);
            $alertas = $usuario-> validarNuevaCuenta();


            // Revisar que alerta este vacio
            if(empty($alertas)){
                //Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();
                

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                } else{
                    //hasear password
                    $usuario->hashPassword();

                    //generar un Token unico
                    $usuario->crearToken();

                    //enviar un email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);

                    $email->enviarConfirmacion();
                    
                    // Crear el usuario
                    $resultado =  $usuario -> guardar();
                    if($resultado){
                        header('Location: /mensaje');
                    }
                    

                    // debuguear($usuario);
                    //no esta registrado
                    
                }

            }
        }

        

        $router->render('auth/crear-cuenta', [
            
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){

        $router->render('auth/mensaje', []);
    }
    
    public static function confirmar(Router $router){
        $alertas = [];

        $token= s($_GET['token']);
        // debuguear($token);
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            //Mostrar mensaje
            Usuario::setAlerta('error','Token no valido');
        } else{
            //Modificar a usuario confirmado
            
            $usuario->confirmado= "1";
            $usuario->token= null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada exitamente'); 

            
        }
        // debuguear($usuario);
        //Obtener alertas
        $alertas = Usuario::getAlertas();
        //Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}