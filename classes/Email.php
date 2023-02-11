<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

use Dotenv\Dotenv as Dotenv;
$dotenv = Dotenv::createImmutable('../includes/.env');
$dotenv->safeLoad();

class Email {

    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        // Crear el objeto de email
        $mail = new PHPMailer();

        // Configurar SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $_ENV['MAIL_PORT'];

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress($this->email);
        $mail->Subject = 'UpTask - Confirma tu cuenta';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html";
        $contenido .= "<p>Hola<strong> " . $this->nombre . "</strong>.</p>";
        $contenido .= "<p>Has creado tu cuenta en UpTask, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['SERVER_HOST'] . "/confirmar?token=" . $this->token . "'>Confirmar Cuenta </a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el mail
        $mail->send();
    }

    public function enviarInstrucciones() {
        // Crear el objeto de email
        $mail = new PHPMailer();

        // Configurar SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $_ENV['MAIL_PORT'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress($this->email);
        $mail->Subject = 'UpTask - Reestablece tu Password';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html";
        $contenido .= "<p>Hola<strong> " . $this->nombre . "</strong>.</p>";
        $contenido .= "<p>Has solicitado reestablecer tu password, sigue el siguiente enlace para hacerlo.</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['SERVER_HOST'] . "/recuperar?token=" . $this->token . "'>Reestablecer Password </a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el mail
        $mail->send();
    }
}