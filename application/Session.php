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

    public static function get($clave = false)
    {
        if (isset($_SESSION[$clave])) {
            return $_SESSION[$clave];
        }
        else {
            return $_SESSION;
        }
    }

    public static function estaAutenticado()
    {
        if (self::get('autenticado')) {
            return true;
        }

        return false;
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

        Session::tiempo();

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
        $role['admin'] = USUARIO_ROL_ADMIN;
        $role['especial'] = USUARIO_ROL_ESPECIAL;
        $role['usuario'] = USUARIO_ROL_USUARIO;

        if (!array_key_exists($level, $role)) {
            throw new Exception('Error de acceso');
        }
        else {
            return $role[$level];
        }
    }

    /**
     * Proporciona acceso únicamente a los niveles incluidos en $level
     * @param array $level
     * @param type $noAdmin
     */
    public static function accesoEstricto(array $level, $noAdmin = false)
    {
        if (!Session::get('autenticado')) {
            header('location:' . BASE_URL . 'error/access/5050');
            exit;
        }

        Session::tiempo();

        if ($noAdmin == false) {
            if (Session::get('level') == 'admin') {
                return;
            }
        }

        //Comparamos el nivel de acceso requerido con el nivel del usuario
        if (count($level)) {
            if (in_array(Session::get('level'), $level)) {
                return;
            }
        }

        header('location:' . BASE_URL . 'error/access/5050');
    }

    public static function accesoViewEstricto(array $level, $noAdmin = false)
    {
        if (!Session::get('autenticado')) {
            return false;
        }

        if ($noAdmin == false) {
            if (Session::get('level') == 'admin') {
                return true;
            }
        }

        //Comparamos el nivel de acceso requerido con el nivel del usuario
        if (count($level)) {
            if (in_array(Session::get('level'), $level)) {
                return true;
            }
        }

        return false;
    }

    public static function tiempo()
    {
        if (!Session::get('tiempo') || !defined('SESSION_TIME')) {
            throw new Exception('No se ha definido el tiempo de sesión');
        }

        if (SESSION_TIME == 0) {
            return;
        }

        if (time() - Session::get('tiempo') > (SESSION_TIME * 60)) {
            Session::destroy();
            header('location:' . BASE_URL . 'error/access/8080');
        }
        else {
            Session::set('tiempo', time());
        }
    }

}
