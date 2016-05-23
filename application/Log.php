<?php

define('DIR_PATH', dirname(__FILE__));
define('DIR_LOGS', DIR_PATH . "/logs/");

class Log
{

    private $_filename;

    public function __construct($_filename = 'log.txt')
    {
        $this->_filename = DIR_LOGS . $_filename;
    }

    public function write($message, $level = LOG_INFO)
    {
        if ($level <= LOG_LEVEL) {
            $usuario = Session::get('id_usuario');

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
