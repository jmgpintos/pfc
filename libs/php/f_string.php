<?php

/*
  f_string.php

  Funciones relacionadas con cadenas(string)
 */


if (!function_exists('mb_ucfirst')) {

    function mb_ucfirst($string, $encoding = 'UTF-8')
    {
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }

}
/*
  Pone en mayúscula la primera letra de cada palabra en $cad
  return -> string.
 */
if (!defined('capitalizar')) {

    function capitalizar($cad)
    {
        $tipo = gettype($cad);
        $r = null;
        switch ($tipo) {
            case "array":
                foreach ($cad as $cadena) {
                    $r[] = capitalizar($cadena);
                }
                break;
            case "string":
                $largo = strlen(trim($cad));
                if ($largo) {
                    $separadores = Array(" ", "-", "(", "[");
                    $particulas = Array("de", "da", "del", "y", "o", "la", "de los",
                        "d'", "das", "al", "no", "en", "ni", "por", "ser", "e");
                    $cad = mb_ucfirst($cad);
                    // $cad = strtolower($cad);
                    $r = strtoupper(substr($cad, 0, 1)); //Primera letra
                    for ($i = 1; $i < $largo; $i++) {
                        // if(strtoupper($cad[$i])=="Ñ")debug("Letra $i de $cad:",$cad[$i]);
                        if (in_array($cad[$i - 1], $separadores)) {
                            $r.=strtoupper($cad[$i]); //Si está detrás de un separador, se pone en mayúsculas
                        } else {
                            $r.=$cad[$i]; //Si no, se deja en minúsculas
                        }
                    }
                    //Está cada palabra capitalizada, ahora hay que poner en minúscula las partículas
                    $r = explode(" ", $r);
                    $largo = count($r);
                    for ($i = 0; $i < $largo; $i++) {
                        if (in_array(strtolower($r[$i]), $particulas)) {
                            $r[$i] = mb_strtolower($r[$i]);
                        }
                    }
                    $r = implode(" ", $r);
                }
                break;
            case "boolean":$r = $cad ? "TRUE" : "FALSE";
                break;
            case "NULL":$r = "NULO";
                break;
            default:
                $r = $cad;
                break;
        }
        return $r;
    }

}
if (!function_exists('ucfirst_utf8')) {

    function ucfirst_utf8($stri)
    {
        if ($stri{0} >= "\xc3")
            return (($stri{1} >= "\xa0") ?
                            ($stri{0} . chr(ord($stri{1}) - 32)) :
                            ($stri{0} . $stri{1})) . substr($stri, 2);
        else
            return ucfirst($stri);
    }

}

/*
  Pasa a mayúsculas $variable (acentos incluidos)
  return -> string
 */
if (!defined('Mayus')) {

    function Mayus($variable)
    {
        $variable = strtr(strtoupper($variable), "àèìòùáéíóúçñäëïöü", "ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
        return $variable;
    }

}

/*
  Pasa a minúsculas $variable (acentos incluidos)
  return -> string
 */
if (!defined('Minus')) {

    function Minus($variable)
    {
        // $variable = strtr(strtolower($variable),utf8_decode("ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ"),utf8_decode("àèìòùáéíóúçñäëïöü"));
        $variable = strtr(strtolower($variable), "ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ", "àèìòùáéíóúçñäëïöü");
        return $variable;
    }

}

/*
  Mira si $haystack empieza por $needle
  return -> boolean
 */
if (!defined('startsWith')) {

    function startsWith($haystack, $needle)
    {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }

}

/*
  Mira si $haystack acaba con $needle
  return -> boolean
 */
if (!defined('endsWith')) {

    function endsWith($haystack, $needle)
    {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }

}



//$cad = 'álvarez';
//echo capitalizar($cad) . PHP_EOL;
//echo capitalizar($cad) . PHP_EOL;
//echo ucfirst($cad) . PHP_EOL;
//echo mb_ucfirst($cad) . PHP_EOL;