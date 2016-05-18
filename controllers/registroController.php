<?php

class registroController extends Controller
{

    private $_registro;

    public function __construct()
    {
        parent::__construct();
        $this->_registro = $this->loadModel('registro');
    }

    public function index()
    {
        if (Session::get('autenticado')) { //Si ya está logueado ir a index
            $this->redireccionar();
        }

        $this->_view->titulo = 'Registro';

        if ($this->getInt('enviar') == 1) {
            $this->_view->datos = $_POST;

            if (!$this->getSql('nombre')) {
                $this->_view->_error = 'Debe introducir su nombre';
                $this->_view->renderizar('index', 'registro');
                exit;
            }

            if (!$this->getAlphaNum('usuario')) {
                $this->_view->_error = 'Debe introducir su nombre de usuario';
                $this->_view->renderizar('index', 'registro');
                exit;
            }

            if ($this->_registro->verificarUsuario($this->getAlphaNum('usuario'))) {
                $this->_view->_error = 'El usuario ' . $this->getAlphaNum('usuario') . ' ya existe';
                $this->_view->renderizar('index', 'registro');
                exit;
            }

            if (!$this->validarEmail($this->getPostParam('email'))) {
                $this->_view->_error = 'La dirección de email no es válida';
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            if ($this->_registro->verificarEmail($this->getSql('email'))) {
                $this->_view->_error = 'El email ' . $this->getSql('email') . ' ya existe';
                $this->_view->renderizar('index', 'registro');
                exit;
            }

            if (!$this->getSql('pass')) {
                $this->_view->_error = 'Debe introducir su password';
                $this->_view->renderizar('index', 'registro');
                exit;
            }

            if ($this->getPostParam('pass') != $this->getPostParam('confirmar')) {
                $this->_view->_error = 'Los passwords no coinciden';
                $this->_view->renderizar('index', 'registro');
                exit;
            }

            $this->_registro->registrarUsuario(
                    $this->getSql('nombre'), $this->getPostParam('usuario'), $this->getSql('pass'),
                    $this->getPostParam('email')
            );


            if (!$this->_registro->verificarUsuario($this->getAlphaNum('usuario'))) {
                $this->_view->_error = 'Se produjo un error al registrar al usuario ';
                $this->_view->renderizar('index', 'registro');
                exit;
            }
                
            $this->_view->_mensaje = 'Registro completado para ' . $this->getAlphaNum('usuario');
        }

        $this->_view->renderizar('index', 'registro');
    }

}
