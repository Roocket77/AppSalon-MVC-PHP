<?php


namespace Clases;
use Model\Usuario;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email{

    public $nombre;
    public $email;
    public $token;
    
    public function __construct($nombre, $email, $token){

        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
        
 
    }

    public function enviarConfirmacion(){

        $mail = new PHPMailer();
        $mail -> isSMTP();

        //Depuracion del stmp
        // $mail->SMTPDebug = 2;

        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail-> setFrom('cuentas@appsalon.com');
        $mail-> addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        //set html
        $mail-> isHTML(TRUE);
        $mail-> CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong> Hola ". $this->nombre . " </strong>Has creado tu cuenta en AppSalon, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p> Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;
    
        // Enviar el email
            $mail->send();
        // try {
            
        //     echo "Mensaje enviado";
        // } catch (Exception $e) {
        //     echo "No se pudo enviar el mensaje. Error de Mailer: {$mail->ErrorInfo}";
        // }




    }
    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail -> isSMTP();

        //Depuracion del stmp
        // $mail->SMTPDebug = 2;

        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail-> setFrom('cuentas@appsalon.com');
        $mail-> addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        //set html
        $mail-> isHTML(TRUE);
        $mail-> CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong> Hola ". $this->nombre . " </strong>Has solicitado reestablecer tu password, sigue el siguiente enlace para hacerlo</p>";
        $contenido .= "<p> Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $this->token . "'>Reestablece tu password</a></p>";
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;
    
        // Enviar el email
            $mail->send();
    }


}