<?php

class Bootstrap
{
/**
 * Trata la petición (objeto Request) y hace la llamada al controlador y método adecuados, pasando los parámetros, si los hubiera.
 * En caso de no existir el controlador llama a DEFAULT_CONTROLLER
 * En caso de no existir el método en el controlador solicitado, llama a DEFAULT_METHOD y no pasa los parámetros
 * 
 * El objeto Request es algo tal que así:
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
 * 
 * @param Request $peticion 
  
 * @throws Exception
 */
    public static function run(Request $peticion)
    {
        $controller = $peticion->getControlador() . 'Controller';
        $rutaControlador = ROOT . 'controllers' . DS . $controller . '.php';
        $metodo = $peticion->getMetodo();
        $args = $peticion->getArgs();
        $metodoExiste = true;

        if (is_readable($rutaControlador)) {
            require_once $rutaControlador;

            /*
             * Instancia un nuevo controlador del tipo solicitado; el warning es porque el controlador principal es abstracto, pero funciona porque en realidad estamos instanciando un objeto de alguna clase controller heredada)
             */
            
            $controller = new $controller; 

            /*
             * Si no existe el método se llama a index SIN parámetros
             */
            if (is_callable(array($controller, $metodo))) {
                $metodo = $peticion->getMetodo();
            }
            else {
                $metodoExiste = false;
                $metodo = DEFAULT_METHOD;
            }

            if ($metodoExiste) { 
                if (isset($args)) {
                    call_user_func_array(array($controller, $metodo), $args);
                }
                else {
                    call_user_func(array($controller, $metodo));
                }
            }
            else {
                call_user_func(array($controller, $metodo));
            }
        }
        else {
            throw new Exception("No encontrado");
        }
    }

}
