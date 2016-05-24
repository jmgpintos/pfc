<?php

/*
 * Clase Controller, de la que heredan todos los controladores
 */

abstract class Controller
{

    protected $_model;
    protected $_modulo;
    protected $_tabla;
    protected $_view;
    protected $_log;

    public function __construct()
    {
        $this->_view = new View(new Request);
        $this->_log = new Log();
    }

    abstract public function index();

    protected function loadModel($modelo)
    {
        $modelo = $modelo . 'Model';
        $rutaModelo = ROOT . 'models' . DS . $modelo . '.php';

        if (is_readable($rutaModelo)) {
            require_once $rutaModelo;
            $modelo = new $modelo;
            return $modelo;
        }
        else {
            throw new Exception("Error de modelo");
        }
    }

    protected function getLibrary($libreria)
    {
        $rutaLibreria = ROOT . 'libs' . DS . $libreria . '.php';
        if (is_readable($rutaLibreria)) {
            require_once $rutaLibreria;
        }
        else {
            throw new Exception("Error al cargar libreria $libreria");
        }
    }

    protected function getTexto($clave)
    {
        //No utilizar con prepare para insertar datos
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = htmlspecialchars($_POST[$clave], ENT_QUOTES); //TODO explicar
            return $_POST[$clave];
        }

        return '';
    }

    protected function getInt($clave)
    {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT); //TODO explicar
            return $_POST[$clave];
        }

        return 0;
    }

    protected function redireccionar($ruta = false)
    {
        if ($ruta) {
            header('location:' . BASE_URL . $ruta);
            exit;
        }
        else {
            header('location:' . BASE_URL);
            exit;
        }
    }

    protected function filtrarInt($int)
    {
        $int = (int) $int;

        if (is_int($int)) {
            return $int;
        }
        else {
            return 0;
        }
    }

    protected function getPostParam($clave)
    {
        if (isset($_POST[$clave])) {
            return $_POST[$clave];
        }
//        put($_POST[$clave]);
    }

    /**
     * Sanitizar cadenas SQL
     * @param string $clave Nombre de la clave del array $_POST a sanitizar
     * @return string Cadena sanitizada
     */
    protected function getSql($clave)
    {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = strip_tags($_POST[$clave]);

            if (!get_magic_quotes_gpc()) {
                $_POST[$clave] = addslashes($_POST[$clave]);
            }
//            puty($_POST[$clave]);
            return trim($_POST[$clave]);
        }
    }

    /**
     * Sanitizar cadenas alfanuméricas
     * Sólo acepta caracteres alfabéticos, numéricos y guión bajo
     * @param string $clave Nombre de la clave del array $_POST a sanitizar
     * @return string Cadena sanitizada
     */
    protected function getAlphaNum($clave)
    {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = (string) preg_replace('/[^A-Z0-9_]/i', '', $_POST[$clave]);
            return trim($_POST[$clave]);
        }
    }

    public function validarEmail($email)
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    /**
     * Trata el parámetro $pagina
     * @param type $pagina
     * @return type
     */
    public function getNumPagina($pagina)
    {
        if (!$this->filtrarInt($pagina)) {
            $pagina = false;
        }
        else {
            $pagina = (int) $pagina;
        }

        Session::set('pagina', $pagina);
        return $pagina;
    }

    /**
     * Pasa los mensajes de sesión a la vista
     */
    public function asignarMensajes()
    {
        if (Session::getMensaje()) {
            $this->_view->assign('_mensaje', Session::getMensaje());
            Session::destroy('mensaje');
        }
        if (Session::getError()) {
            $this->_view->assign('_error', Session::getError());
            Session::destroy('error');
        }
        
        $this->_view->assign('_pagina', Session::get('pagina'));
    }

}
