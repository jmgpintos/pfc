<?php

define('DIR_PATH', dirname(__FILE__));
define('DIR_LOGS', DIR_PATH . "/logs/");

class Log
{

    /**
     * 
     * @var String Nombre del fichero de log
     */
    private $_filename;

    public function __construct($_filename = 'log.txt')
    {
        $this->_filename = DIR_LOGS . $_filename;
    }

    /**
     * Agrega líneas al archivo de log
     * 
     * @param type $message
     * @param type $level Nivel mínimo para guardar los mensajes
     */
    public function write($message, $level = LOG_NOTICE)
    {
        if ($level <= LOG_LEVEL) {
            if (Session::estaAutenticado()) {
                $usuario = Session::get('id_usuario');
            }
            else {
                $usuario = 'No autenticado';
            }
            $handle = fopen($this->_filename, 'a+');

            fwrite($handle,
                    'LEVEL: ' . $level . ' - '
                    . date('Y-m-d G:i:s')
                    . ' - Usuario: ' . $usuario . ' - '
                    . $message . "\n");

            fclose($handle);
        }
    }

}
