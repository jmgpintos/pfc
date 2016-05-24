<?php

if (DEBUG) {
    define('BASE_URL', 'http://bancodeimagenes.lan/'); //Para acceder a archivos desde las vistas
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'plas');
    define('DB_NAME', 'banco');
    define('DB_CHAR', 'utf8');

    define('TABLES_PREFIX', '');

    define('MAIL_FROM', 'admin@bancodeimagenes.lan');
}
else {
    define('BASE_URL', 'http://bimagenes.hol.es/'); //Para acceder a archivos desde las vistas 
    define('DB_HOST', 'localhost');
    define('DB_USER', 'u667159124_usu');
    define('DB_PASS', 'Pa$$word');
    define('DB_NAME', 'u667159124_banco');
    define('DB_CHAR', 'utf8');

    define('TABLES_PREFIX', '');

    define('MAIL_FROM', 'admin@bimagenes.hol.es');
}

define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_LAYOUT', 'default');

define('APP_NAME', 'Banco de imágenes');
define('APP_SLOGAN', 'Life\'s better when we\'re connected');
define('APP_COMPANY', 'José Manuel García Pintos');

define('SESSION_TIME', 15);
define('HASH_KEY', '573ca1f7efa62');

//estados usuario
define('USUARIO_ESTADO_ACTIVADO', 1);
define('USUARIO_ESTADO_NO_ACTIVADO', 0);

//roles usuario. Ojo, no es lo mismo que la tabla usuarios.
define('USUARIO_ROL_ADMIN', 3);
define('USUARIO_ROL_ESPECIAL', 2);
define('USUARIO_ROL_USUARIO', 1);
define('DEFAULT_ROLE', 3); //Ojo el 3 significa usuario en la tabla rol

define('REGISTROS_POR_PAGINA', 5);

//Los niveles de LOG ya están definidos en el código fuente de PHP
//define('LOG_EMERG', 0);
//define('LOG_ALERT', 1);
//define('LOG_CRIT', 2);
//define('LOG_ERR', 3);
//define('LOG_WARNING', 4);
//define('LOG_NOTICE', 5);
//define('LOG_INFO', 6);
//define('LOG_DEBUG', 7);

if (DEBUG) {
    define('LOG_LEVEL', LOG_DEBUG);
}
else {
    define('LOG_LEVEL', LOG_NOTICE);
}


define('ACCION_EDITAR', 'editar');
define('ACCION_NUEVO', 'nuevo');