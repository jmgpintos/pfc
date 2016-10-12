<?php

/**
 * Lee la url solicitada y la convierte en un objeto de tipo Request, que consta de:
 * String controlador
 * String metodo
 * Array argumentos
 * 
 * object(Request)#2 (3) {
  ["_controlador":"Request":private]=>
  string(9) "categoria"
  ["_metodo":"Request":private]=>
  string(6) "editar"
  ["_argumentos":"Request":private]=>
  array(1) {
  [0]=>
  string(3) "438"
  }
  }
 */
class Request
{

    private $_controlador;
    private $_metodo;
    private $_argumentos;

    public function __construct()
    {
        if (isset($_GET['url'])) {//  && $_GET['url'] != 'index.php'
//            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
//            $url = explode('/', $url);
//            $url = array_filter($url);
            
            $url = explode(
                    '/', filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL)
            );


            $this->_controlador = strtolower(array_shift($url));
            $this->_metodo = strtolower(array_shift($url));
            $this->_argumentos = $url;
        }

        if (!$this->_controlador) {
            $this->_controlador = DEFAULT_CONTROLLER;
        }

        if (!$this->_metodo) {
            $this->_metodo = DEFAULT_METHOD;
        }

        if (!isset($this->_argumentos)) {
            $this->_argumentos = array();
        }
    }

    public function getControlador()
    {
        return $this->_controlador;
    }

    public function getMetodo()
    {
        return $this->_metodo;
    }

    public function getArgs()
    {
        return $this->_argumentos;
    }

}
