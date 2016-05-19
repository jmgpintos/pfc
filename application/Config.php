<?php

define('DEBUG', true);

if (DEBUG) {
    define('BASE_URL', 'http://bancodeimagenes.lan/'); //Para acceder a archivos desde las vistas
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'plas');
    define('DB_NAME', 'banco');
    define('DB_CHAR', 'utf8');

    define('TABLES_PREFIX', '');
    
    define('MAIL_FROM','bancodeimagenes.lan');
}
else {
    define('BASE_URL', 'http://bimagenes.hol.es/'); //Para acceder a archivos desde las vistas 
    define('DB_HOST', 'localhost');
    define('DB_USER', 'u667159124_usu');
    define('DB_PASS', 'Pa$$word');
    define('DB_NAME', 'u667159124_banco');
    define('DB_CHAR', 'utf8');

    define('TABLES_PREFIX', '');
    
    define('MAIL_FROM','bimagenes.hol.es');
}

define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_LAYOUT', 'default');

define('APP_NAME', 'Nombre app');
define('APP_SLOGAN', 'Slogan app');
define('APP_COMPANY', 'Company app');

define('SESSION_TIME', 1);
define('HASH_KEY', '573ca1f7efa62');
