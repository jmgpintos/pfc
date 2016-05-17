<?php

class Session
{

    public static function init()
    {
        session_start();
    }

    public static function destroy($clave = false)
    {
        if ($clave) {
            if (is_array($clave)) {
                for ($i = 0; $i < count($clave); $i++) {
                    if (isset($_SESSION[$clave[$i]])) {
                        unset($_SESSION[$clave[$i]]);
                    }
                }
            }
            else {
                if (isset($_SESSION[$clave])) {
                    unset($_SESSION[$clave]);
                }
            }
        }
        else {
            session_destroy();
        }
    }

    public static function set($clave, $valor)
    {
        if (!empty($clave)) {
            $_SESSION[$clave] = $valor;
        }
    }

    public static function get($clave=false)
    {
        if (isset($_SESSION[$clave])) {
            return $_SESSION[$clave];
        }
        else {
            return $_SESSION;
        }
    }

    /**
     * 
     * @param type $level Nivel de usuario mínimo requerido para tener permiso
     */
    public static function acceso($level)
    {
        if (!Session::get('autenticado')) {
            header('location:' . BASE_URL . 'error/access/5050');
            exit;
        }

        //Comparamos el nivel de acceso requerido con el nivel del usuario
        if (Session::getLevel($level) > Session::getLevel(Session::get('level'))) {
            header('location:' . BASE_URL . 'error/access/5050');
            exit;
        }
    }

    /**
     * Para restringir código en las vistas
     * @param type $level Nivel de usuario mínimo requerido para tener permiso
     * 
     * @return boolean
     */
    public static function accesoView($level)
    {
        if (!Session::get('autenticado')) {
            return false;
        }

        //Comparamos el nivel de acceso requerido con el nivel del usuario
        if (Session::getLevel($level) > Session::getLevel(Session::get('level'))) {
            return false;
        }
        return true;
    }

    public static function getLevel($level)
    {
        $role['admin'] = 3;
        $role['especial'] = 2;
        $role['usuario'] = 1;

        if (!array_key_exists($level, $role)) {
            throw new Exception('Error de acceso');
        }
        else {
            return $role[$level];
        }
    }

}
