<?php

class Bootstrap
{

    public static function run(Request $peticion)
    {
        $controller = $peticion->getControlador() . 'Controller';
        $rutaControlador = ROOT . 'controllers' . DS . $controller . '.php';
        $metodo = $peticion->getMetodo();
        $args = $peticion->getArgs();
        
        
        if(is_readable($rutaControlador)){
            require_once $rutaControlador;
            
//            echo $controller;
            
            $controller = new $controller; //Instancia un nuevo controlador del tipo solicitado 
            //                             //(el warning es porque el controlador principal es abstracto)
            
//            var_dump (get_object_vars($controller));
            
            if(is_callable(array($controller, $metodo))){
                $metodo = $peticion->getMetodo();
            }else{
                $metodo = 'index';
            }
            
            if(isset($rgs)){
                call_user_func_array(array($controller,$metodo),$args);
            }else{
                call_user_func(array($controller,$metodo));                
            }
            
            
        }else{
            throw new Exception("No encontrado");
        }
    }

}
