<?php

define('DEBUG', true);
//echo "index.php";exit;
if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
define('DS', DIRECTORY_SEPARATOR); //Carácter separador de directorios
define('ROOT', realpath(dirname(__FILE__)) . DS); //Ruta raíz de la aplicación. La utilizamos para includes
define('LIB_PATH', ROOT . 'libs' . DS);
define('APP_PATH', ROOT . 'application' . DS); //Ruta del directorio application

try {
    require_once APP_PATH . 'Config.php';
    require_once APP_PATH . 'Request.php'; //recibe peticiones por la url y se las pasa a bootstrap
    require_once APP_PATH . 'Bootstrap.php'; //llama al controller necesario (en carpeta controllers)
    require_once APP_PATH . 'Controller.php';
    require_once APP_PATH . 'Model.php';
    require_once APP_PATH . 'View.php';
    require_once APP_PATH . 'Registro.php';
    require_once APP_PATH . 'Database.php';
    require_once APP_PATH . 'Session.php';
    require_once APP_PATH . 'Hash.php';
    require_once APP_PATH . 'Log.php';

    require_once LIB_PATH . 'helpers/helper-functions.php';
    require_once LIB_PATH . 'php/f_datetime.php';
    require_once LIB_PATH . 'php/f_string.php';

//    echo Hash::getHash('SHA1', '1234', HASH_KEY);exit;
    Session::init();

    $r = new Request();
//    vardumpy($r);

//    put($r->getControlador());
//    put($r->getMetodo());
//    vardump($r->getArgs());
//    vardump(info_sesion());
//    puty(__FILE__);

    Bootstrap::run(new Request());
} catch (Exception $e) {
    echo $e->getMessage();
}