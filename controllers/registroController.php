<?php

class registroController extends Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->_model = $this->loadModel('registro');
    }

    public function index()
    {
        if (Session::estaAutenticado()) { //Si ya está logueado ir a index
            $this->redireccionar();
        }

        $this->_view->assign('titulo', 'Registro');

        if ($this->getInt('enviar') == 1) {
            $this->_view->assign('datos', $_POST);

            if (!$this->validar()) {
                $this->_view->renderizar('index', 'registro');
                exit;
            }


            //TODO añadir campos

            $this->_model->registrarUsuario(
                    $this->getSql('nombre'), $this->getPostParam('usuario'), $this->getSql('pass'),
                    $this->getPostParam('email')
            );

            $usuario = $this->_model->verificarUsuario($this->getAlphaNum('usuario'));


            if (!$usuario) {
                $this->_view->assign('_error', 'Se produjo un error al registrar al usuario ');
                $this->_view->renderizar('index', 'registro');
                exit;
            }

            $mail = $this->correo($usuario);

            if (!$mail->send()) {
                $this->_view->assign('_error', "Mailer Error: " . $mail->ErrorInfo);
                $this->_view->renderizar('index', 'registro');
                exit;
            }

            $this->_view->assign('_mensaje',
                    'Registro completado, revise su email para activar su cuenta');
        }

        $this->_view->renderizar('index', 'registro');
    }

    public function activar($id, $codigo)
    {
        $this->_view->assign('titulo', 'Activar cuenta');
        $this->_view->assign('tituloView', 'Activación de cuenta');

        if (!$this->filtrarInt($id) || !$this->filtrarInt($codigo)) {
            $this->_view->assign('_error', 'Esta cuenta no existe');
            $this->_view->renderizar('activar', 'registro');
            exit;
        }
        $row = $this->_model->getUsuario(
                $this->filtrarInt($id), $this->filtrarInt($codigo)
        );

        if (!$row) {
            $this->_view->assign('_error', 'Esta cuenta no existe');
            $this->_view->renderizar('activar', 'registro');
            exit;
        }

        if ($row['estado'] == USUARIO_ESTADO_ACTIVADO) {
            $this->_view->assign('_error', 'Esta cuenta ya ha sido activada');
            $this->_view->renderizar('activar', 'registro');
            exit;
        }

        $this->_model->activarUsuario(
                $this->filtrarInt($id), $this->filtrarInt($codigo)
        );


        $row = $this->_model->getUsuario(
                $this->filtrarInt($id), $this->filtrarInt($codigo)
        );
        if ($row['estado'] == USUARIO_ESTADO_NO_ACTIVADO) {
            $this->_view->assign('_error',
                    'Error al activar la cuenta, por favor inténtelo más tarde');
            $this->_view->renderizar('activar', 'registro');
            exit;
        }

        $this->_view->assign('_mensaje', 'Su cuenta ha sido activada');
        $this->_view->renderizar('activar', 'registro');
    }

    public function correo($usuario)
    {
        $this->getLibrary('class.phpmailer');
        $mail = new PHPMailer();

        $mail->From = MAIL_FROM;
        $mail->FromName = html_entity_decode('Banco de imágenes');
        $mail->Subject = html_entity_decode('Activación de cuenta de usuario');
        $mail->Body = 'Hola <strong>' . $this->getSql('nombre') . '</strong>,'
                . ' <p>Se ha registrado en ' . APP_NAME . '.'
                . ' Para activar su cuenta haga click sobre el siguiente enlace:</p>'
                . ' <a href="' . BASE_URL . 'registro/activar/'
                . $usuario['id'] . '/' . $usuario['codigo'] . '">'
                . 'Activar registro' . '</a>';
//                    . BASE_URL . 'registro/activar/'
//                    . $usuario['id'] . '/' . $usuario['codigo'] . '</a>';
        $mail->AltBody = 'Su servidor de correo no soporta HTML';
        $mail->addAddress($this->getPostParam('email'));
        $mail->CharSet = 'UTF-8';

        return $mail;
    }

    public function validar()
    {
        $valido = true;
        if (!$this->getSql('nombre')) {
            $this->_view->assign('_error', 'Debe introducir su nombre');
            $valido = false;
        }
        elseif (!$this->getAlphaNum('usuario')) {
            $this->_view->assign('_error', 'Debe introducir su nombre de usuario');
            $valido = false;
        }
        elseif ($this->_model->verificarUsuario($this->getAlphaNum('usuario'))) {
            $this->_view->asign('_error',
                    'El usuario ' . $this->getAlphaNum('usuario') . ' ya existe');
            $valido = false;
        }
        elseif (!$this->validarEmail($this->getPostParam('email'))) {
            $this->_view->assign('_error', 'La dirección de email no es válida');
            $valido = false;
        }
        elseif ($this->_model->verificarEmail($this->getSql('email'))) {
            $this->_view->assign('_error', 'El email ' . $this->getSql('email') . ' ya existe');
            $valido = false;
        }
        elseif (!$this->getSql('pass')) {
            $this->_view->assign('_error', 'Debe introducir su password');
            $valido = false;
        }
        elseif ($this->getPostParam('pass') != $this->getPostParam('confirmar')) {
            $this->_view->assign('_error', 'Los passwords no coinciden');
            $valido = false;
        }

        return $valido;
    }

}
