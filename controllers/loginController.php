<?php

class loginController extends Controller
{

//    private $_model;

    public function __construct()
    {
        parent::__construct();
        $this->_model = $this->loadModel('login');
    }

    public function index()
    {
        if (Session::estaAutenticado()) {
            $this->redireccionar();
        }
        $this->_view->assign('titulo', 'Iniciar Sesión');

        if ($this->getInt('enviar') == 1) {
            $this->_view->assign('datos', $_POST);

            $row = $this->validarUsuario();

            Session::set('autenticado', true);
            Session::set('level', $row['rol']);
            Session::set('usuario', $row['username']);
            Session::set('id_usuario', $row['id']);
            Session::set('tiempo', time());
            $this->_model->cambiarUltimoAcceso($row['id']);
            $this->redireccionar();
        }

        $this->_view->renderizar('index', 'login');
    }

    public function cerrar()
    {
        Session::destroy();
        
        //Iniciamos sesión de nuevo para poder pasar el mensaje 
        Session::init();
        Session::setMensaje('Sesión finalizada');
        
        $this->redireccionar();
    }

    private function validarUsuario()
    {
        $error = false;
        $mensaje = null;

        if (!$this->getAlphaNum('usuario')) {
            $mensaje = 'Debe introducir un nombre de usuario';
            $error = true;
        }
        else if (!$this->getSql('pass')) {
            $mensaje = 'Debe introducir una contraseña';
            $error = true;
        }

        $row = $this->_model->getUsuario(
                $this->getAlphaNum('usuario'), $this->getSql('pass')
        );

        if (!$error) {
            if (!$row) {
                $mensaje = 'Usuario y/o contraseña incorrectos';
                $error = true;
            }
            else if ($row['estado'] != 1) {
                $mensaje = 'Este usuario no está habilitado';
                $error = true;
            }
        }

        if ($error) {
            $this->_view->assign('_error', $mensaje);
            $this->_view->renderizar('index', 'login');
            exit;
        }

        Session::setMensaje( 'Usuario logueado como <strong>' . $row['username'] .'</strong>');
        return $row;
    }

}
