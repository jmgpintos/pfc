<?php

/*
 * Clase Session
 * 
 * Métodos para leer y escribir variables de sesión
 * Métodos para controlar niveles de acceso
 * 
 */

class Session
{

    /**
     * Inicia sesión PHP
     */
    public static function init()
    {
        session_start();
    }

    /**
     * Finaliza sesión PHP o borra una variable de sesión determinada
     * @param string $clave Nombre de variable de sesión a borrar. Si es false, se finaliza la sesión
     */
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

    /**
     * Establece el valor de una variable de sesión
     * @param string $clave Nombre de la variable
     * @param type $valor Valor de la variable
     */
    public static function set($clave, $valor)
    {
        if (!empty($clave)) {
            $_SESSION[$clave] = $valor;
        }
    }

    /**
     * Recupera el valor de una variable de sessión
     * @param string $clave Nombre de la variable
     * @return type Valor de la variable
     */
    public static function get($clave = false)
    {
        if (isset($_SESSION[$clave])) {
            return $_SESSION[$clave];
        }
        else {
            if (count($_SESSION)) {
                return $_SESSION;
            }
            else {
                return null;
            }
        }
    }

    /**
     * Devuelve el id del usuario autenticado
     * 
     * @return int
     */
    public function getId()
    {
        if (self::estaAutenticado()) {
            return self::get('id_usuario');
        }
    }

    /**
     * Devuelve true si el usuario está autenticado
     * @return boolean
     */
    public static function estaAutenticado()
    {
        if (isset($_SESSION['autenticado'])) {
            return true;
        }

        return false;
    }

    /**
     * Devuelve true si el usuario autenticado tiene el perfil de ADMIN
     * 
     * @return boolean
     */
    public static function esAdmin()
    {
        return(Session::getLevel(Session::get('level')) == USUARIO_ROL_ADMIN);
    }

    /**
     * Devuelve true si el usuario autenticado tiene el perfil de ESPECIAL
     * 
     * @return boolean
     */
    public static function esEspecial()
    {
        return(Session::getLevel(Session::get('level')) == USUARIO_ROL_ESPECIAL);
    }

    /* METODOS PARA LEER/ESCRIBIR MENSAJES Y ERRORES */

    //mensaje y error son variables de sesión para pasar mensajes 
    //entre vistas al usar la función redireccionar
    /**
     * Establece el valor  del mensaje
     * 
     * @param string $msg Texto del mensaje
     */
    public static function setMensaje($msg, $tipo = 'mensaje')
    {
        self::set('mensaje', $msg);
    }

    /**
     * Establece el valor  del error
     * @param string $msg Texto del error
     */
    public static function setError($msg)
    {
        self::set('error', $msg);
        ;
    }

    /**
     * Recupera el texto de error
     * @return boolean|string Texto del error o false si no existe la variable de sesión 'error'
     */
    public static function getError()
    {
        if (isset($_SESSION['error'])) {
            return self::get('error');
        }

        return false;
    }

    /**
     * Recupera el texto de mensaje
     * @return boolean|string Texto del mensaje o false si no existe la variable de sesión 'mensaje'
     */
    public static function getMensaje()
    {
        if (isset($_SESSION['mensaje'])) {
            return self::get('mensaje');
        }

        return false;
    }

    /* FIN METODOS PARA LEER/ESCRIBIR MENSAJES Y ERRORES */

    /* METODOS PARA CONTROLAR ACCESO */

    /**
     * Controla el acceso a los métodos según perfil de usuario
     * 
     * @param string $level Nivel de usuario mínimo requerido para tener permiso
     */
    public static function acceso($level)
    {
        if (!Session::estaAutenticado()) {
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
        if (!Session::estaAutenticado()) {
            return false;
        }

        //Comparamos el nivel de acceso requerido con el nivel del usuario
        if (Session::getLevel($level) > Session::getLevel(Session::get('level'))) {
            return false;
        }
        return true;
    }

    /**
     * Recupera el número entero que corresponde al nivel enviado
     * @param string $level Nivel de usuario
     * @return int Entero que representa el nivel de usuario
     * @throws Exception
     */
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
        if (!Session::estaAutenticado()) {
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
        if (!Session::estaAutenticado()) {
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

    /* FIN METODOS PARA CONTROLAR ACCESO */

    /**
     * Comprueba si ha caducado el tiempo de sesión.
     * En caso de que haya caducado, envía a una página de error.
     * Si no ha caducado, actualiza el tiempo para mantener la sesión activa.
     * @return void
     * @throws Exception
     */
    public static function tiempo()
    {
        if (self::estaAutenticado()) {
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

}
