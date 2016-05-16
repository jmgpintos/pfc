<?php

/**
 * Helper Functions
 * These may well be defined by a chile class.
 */
if (!function_exists('info_sesion')) {

    function info_sesion($sep = false)
    {
        if (!$sep) {
            $sep = "\n";
        }
        if (Session::get('autenticado')) {
            $sesion = Session::get();
            return '<strong>SESI&OacuteN:</strong> ' . info_array($sesion, $sep);
        }
    }

}

if (!function_exists('info_array')) {

    function info_array(array $arr, $sep = ' | ', $esArray = false)
    {
        $s = $l = $r = "";
        foreach ($arr as $clave => $v) {
            $tipo = gettype($v);
            switch ($tipo) {
                case "array":
                    $r = 'array-> [' . info_array($v, '; ', true) . ']';
                    break;
                case "boolean":
                    $r = Session::get($clave) ? 'TRUE' : 'FALSE';
                    break;
                case "NULL":
                    $r = 'NULL';
                    break;
                default:
                    $r = $v;
            }
            if (!empty($r)) {
                if (!$esArray) {
                    $l .= "<strong>" . $clave . ":</strong> " . $r . $sep;
                } else {
                    $l .= "<strong>" . $clave . " =></strong> " . $r . $sep;
                }
            }

            $s = rtrim($l, $sep);
        }
        return ($s);
    }

}

if (!function_exists('vardump')) {

    function vardump($msg = null)
    {
        $args = func_get_args();
        if (is_string($msg)) {
            array_shift($args); // remove msg from the args array
            $msg = "<b>$msg</b>\n";
        } else {
            $msg = "";
        }


        echo "<pre>$msg";
        var_dump($args);
        echo "</pre>";
    }

}

if (!function_exists('vardumpy')) {

    function vardumpy($msg = null)
    {
        vardump($msg);
        exit;
    }

}

if (!function_exists('put')) {

    function put($text = 'xxx')
    {
        echo "<pre>$text</pre>\n";
    }

}

if (!function_exists('puty')) {

    function puty($text)
    {
        put($text);
        exit;
    }

}

if (!function_exists('put_e')) {

    function put_e($msg)
    {
        $msg = escapeltgt($msg);
        echo "<pre>$msg</pre>";
    }

}

if (!function_exists('vardump_e')) {

    function vardump_e($value, $msg = null)
    {
        if ($msg)
            $msg = "<b>$msg</b>\n";
        echo "<pre>$msg" . (escapeltgt(print_r($value, true))) . "</pre>\n\n";
    }

}

/**
 * stripSlashesDeep
 * recursively do stripslahes() on an array or string.
 * Only define if not already defined.
 * @param array|string $value either a string or an array of strings/arrays ...
 * @return original $value stripped clean of slashes.
 */
if (!function_exists('stripSlashesDeep')) {

    function stripSlashesDeep($value)
    {
        $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
        return $value;
    }

}



// like above but for mysql_real_escape_string()

if (!function_exists('mysqlEscapeDeep')) {

    function mysqlEscapeDeep($db, $value)
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $val[$k] = mysqlEscapeDeep($db, $v);
            }
            return $val;
        } else {
            return $db->real_escape_string($value);
        }
    }

}

// Change < and > into "&lt;" and "&gt;" entities

if (!function_exists('escapeltgt')) {

    function escapeltgt($value)
    {
        $value = preg_replace(array("/</", "/>/"), array("&lt;", "&gt;"), $value);
        return $value;
    }

}

// varprint makes value readable via print_r

if (!function_exists('varprint')) {

    function varprint($value, $msg = null)
    {
        if ($msg)
            $msg = "<b>$msg</b>\n";
        echo "<pre>$msg" . (escapeltgt(print_r($value, true))) . "</pre>\n\n";
    }

}

if (!function_exists('arrayToObjectDeep')) {

    function arrayToObjectDeep($array)
    {
        if (!is_array($array)) {
            return $array;
        }

        $object = new stdClass();

        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name => $value) {
                $name = strtolower(trim($name));
                if (!empty($name)) {
                    $object->$name = arrayToObjectDeep($value);
                }
            }
            return $object;
        } else {
            return FALSE;
        }
    }

}

if (!function_exists('fecha_mes')) {

    function fecha_mes($param = null, $sep = "/", $mes_corto = true, $hora = false)
    {
        $r = 5;
        if (!isset($param)) {
            return null;
        }
        $meses = array(
            'enero',
            'febrero',
            'marzo',
            'abril',
            'mayo',
            'junio',
            'julio',
            'agosto',
            'septiembre',
            'octubre',
            'noviembre',
            'diciembre'
        );
        $meses_cortos = array('ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic');

        if ($mes_corto) {
            $meses = $meses_cortos;
        }

        $fecha = explode('-', substr($param, 0, 10));
        $hora = substr($param, -8);

        if (substr($fecha[1], 0, 1) == 0) { //quitar el 0 inicial al indice del mes
            $fecha[1] = substr($fecha[1], -1);
        }

        if ($fecha[1] < 1 || $fecha[1] > 12) {
            return null;
        }

        $r = $fecha[2] . $sep . $meses[$fecha[1] - 1] . $sep . $fecha[0];

        if ($hora === true) {
            $r.=' - ' . $hora;
        }

        return $r;
    }

}

if (!function_exists('fecha_completa')) {

    function fecha_completa($fecha)
    {
        return ltrim(fecha_mes($fecha, ' de ', false), 0);
    }

}

if (!function_exists('formato_telefono')) {

    function formato_telefono($t, $sep = ' ')
    {
        if (strlen($t) != 9) {
            if ($t == '0') {
                return null;
            }
            return $t;
        }
        return substr($t, 0, 3) . $sep . substr($t, 3, 3) . $sep . substr($t, -3);
    }

}

if (!function_exists('limpia_espacios')) {

    function limpia_espacios($cadena)
    {
        return str_replace(' ', '', $cadena);
    }

}
if (!function_exists('limpiar')) {

    function limpiar($String)
    {
        $String = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä'), "a", $String);
        $String = str_replace(array('Á', 'À', 'Â', 'Ã', 'Ä'), "A", $String);
        $String = str_replace(array('Í', 'Ì', 'Î', 'Ï'), "I", $String);
        $String = str_replace(array('í', 'ì', 'î', 'ï'), "i", $String);
        $String = str_replace(array('é', 'è', 'ê', 'ë'), "e", $String);
        $String = str_replace(array('É', 'È', 'Ê', 'Ë'), "E", $String);
        $String = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º'), "o", $String);
        $String = str_replace(array('Ó', 'Ò', 'Ô', 'Õ', 'Ö'), "O", $String);
        $String = str_replace(array('ú', 'ù', 'û', 'ü'), "u", $String);
        $String = str_replace(array('Ú', 'Ù', 'Û', 'Ü'), "U", $String);
        $String = str_replace(array('[', '^', '´', '`', '¨', '~', ']'), "", $String);
        $String = str_replace("ç", "c", $String);
        $String = str_replace("Ç", "C", $String);
        $String = str_replace("ñ", "n", $String);
        $String = str_replace("Ñ", "N", $String);
        $String = str_replace("Ý", "Y", $String);
        $String = str_replace("ý", "y", $String);

        $String = str_replace("&aacute;", "a", $String);
        $String = str_replace("&Aacute;", "A", $String);
        $String = str_replace("&eacute;", "e", $String);
        $String = str_replace("&Eacute;", "E", $String);
        $String = str_replace("&iacute;", "i", $String);
        $String = str_replace("&Iacute;", "I", $String);
        $String = str_replace("&oacute;", "o", $String);
        $String = str_replace("&Oacute;", "O", $String);
        $String = str_replace("&uacute;", "u", $String);
        $String = str_replace("&Uacute;", "U", $String);
        return $String;
    }

}

if (!function_exists('ellipsis')) {

    function ellipsis($cad = false, $num = 20, $ellipsis = '...')
    {
        if (!$cad) {
            return null;
        }
        if (strlen($cad) > $num) {
            return mb_substr($cad, 0, $num - strlen($ellipsis), 'UTF-8') . $ellipsis;
        } else {
            return $cad;
        }
    }

}

if (!function_exists('getLibrary')) {

    function getLibrary($libreria)
    {

        $rutaLibreria = LIB_PATH . $libreria . '.php';

        if (is_readable($rutaLibreria)) {
            require_once $rutaLibreria;
        } else {
            throw new Exception('Librería ' . $libreria . ' no encontrada');
        }
    }

}

if (!function_exists('subir_imagen')) {

    function subir_imagen($ruta)
    {
        getLibrary('upload' . DS . 'class.upload');
        if (substr($ruta, -1) != DS) {
            $ruta .= DS;
        }
        $upload = new upload($_FILES['imagen'], 'es_ES');
//        $upload->file_max_size = MAX_SIZE_IMG;
        $upload->allowed = array('image/*');
        $upload->file_new_name_body = 'upl_' . uniqid();
        $upload->process($ruta);
        return $upload;
    }

}

if (!function_exists('array_to_str')) {

    function array_to_str($arr)
    {
        $r = null;
        foreach ($arr as $key => $value) {
            $key = ltrim($key, ":");
            $r .= "[" . $key . "=>" . $value . "], ";
        }
        $r = rtrim($r, ", ");
        return $r;
    }

}