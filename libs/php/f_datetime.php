<?php

class FechaHora {

//Arrays de nombres de días y meses
    private static $_dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves",
        "Viernes", "S&aacute;bado");
    private static $_meses = array("enero", "febrero", "marzo", "abril", "mayo",
        "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
    private static $_meses_corto = array("ene", "feb", "mar", "abr", "may", "jun",
        "jul", "ago", "sep", "oct", "nov", "dic");

    const HOY_TIMESTAMP = 'timestamp';
    const HOY_TEXTO = 'texto';
    const HOY_HTML5 = 'html5';
    const HOY_CORTO = 'corto';
    const HOY_INVERSO = 'inverso';

    function __construct()
    {
        
    }

    /**
     * 
      /*  Convierte fecha en texto
     * 
     * @param String $fecha fecha ("AAAA-MM-DD") a convertir
     * @param boolean $dow si = true -> devuelve también el día de la semana
     * @param boolean $year si = true -> devuelve también el año
     * 
     * @return string|boolean fecha en texto o false si fecha=nula o '00-00-0000
     */
    public static function fechaATexto($fecha, $dow = false, $year = true)
    {
        if (is_null($fecha)) {
            return $fecha;
        }

        //Separamos la fecha en día, mes y año
        $array_fecha = explode("-", $fecha);
        $anno = $array_fecha[0];
        $mes = $array_fecha[1];
        $dia = $array_fecha[2];
        if ($mes == '00') {
            return false;
        }

        //Quitar 0 inicial del día
        $dia_texto = $dia;
        if ($dia < 10) {
            $dia_texto = substr($dia, -1);
        }

        //Poner mes en texto 
        $mes_texto = self::$_meses[$mes - 1];

        //Construimos la cadena de fecha
        $fecha_texto = $dia_texto . " de " . $mes_texto;
        if ($year) {
            $fecha_texto .=" de " . $anno;
        }

        if ($dow === true) { //Se añade el día de la semana
            $fecha_texto = self::$_dias[date('w', strtotime($fecha))] . ", " . $fecha_texto;
        }

        return $fecha_texto;
    }

    /**
     * Devuelve la fecha en formato corto
     * 
     * @param string $fecha fecha ("Y-m-d) a convertir
     * @param boolean $conAnno si true, devuelve tb año
     * @param string $separador caracter que separa año,mes y dia en $fecha
     * 
     * @return string|boolean Fecha corta o false si $fecha no es fecha
     */
    public static function fechaCorta($fecha, $conAnno = true, $separador = "-")
    {
//        $meses = self::$_meses_corto;
        //comprobar separador
        if (empty($separador) || !strpos($fecha, $separador)) {
            return false;
        }
        //Separamos la fecha en día, mes y año
        $array_fecha = explode($separador, $fecha);
        $anno = $array_fecha[0];
        $mes = $array_fecha[1];
        $dia = $array_fecha[2];

        //Poner mes en texto, cuidado con fechas incorrectas
        if ($mes > 0) {
            $mes_texto = self::$_meses_corto[$mes - 1];
        } else {
            return false;
        }

        //Quitar 0 inicial del día
        $dia_texto = $dia;
        if ($dia < 10) {
            $dia_texto = substr($dia, -1);
        }

        //Construimos la cadena de fecha
        $fecha_corta = $dia_texto . " " . ucwords($mes_texto);
        if ($conAnno) {
            $fecha_corta .= " " . substr($anno, -2);
        } elseif ($conAnno == 2) {
            $fecha_corta .= " " . $anno;
        }

        return $fecha_corta;
    }

    /**
     * Devuelve una fecha en formato D-M-A
     * @param string $fecha fecha ("AAAA-MM-DD") a convertir
     * @param string $separador separador para la fecha retornada
     * 
     * @return string Fecha corta en formato D-M-A (si separador='-')
     */
    public static function fechaDMA($fecha, $separador = '-')
    {
        $partes = explode('-', $fecha);
        $r = '';
        for ($index = count($partes) - 1; $index >= 0; $index--) {
            $r .= $partes[$index] . $separador;
        }
        return rtrim($r, $separador);
    }

    /**
     * Devuelve años transcurridos desde $dob
     * 
     * @param string $dob fecha en formato ("Y-m-d") 
     * 
     * @return int
     */
    public static function edad($dob)
    {
        if (!empty($dob)) {
            $birthdate = new DateTime($dob);
            $today = new DateTime('today');
            $age = $birthdate->diff($today)->y;
            return $age;
        } else {
            return 0;
        }
    }

    /**
     * Devuelve la fecha/hora actual en el formato solicitado
     * 
     * @param string $formato
     * @return string
     */
    public static function Hoy($formato = 'timestamp')
    {
        $r = '';

        switch ($formato) {
            case 'timestamp':
                $r = date('Y-m-d H:i:s');
                break;

            case 'texto':
                $r = self::fechaATexto(date('Y-m-d'));
                break;

            case 'html5':
                $r = date('Y-m-d');
                break;

            case 'corto':
                $r = self::fechaCorta(date('Y-m-d'));
                break;

            case 'inverso':
                $r = date('ymd');
                break;

            default:
                break;
        }

        return $r;
    }

    public static function HoraCorta($hora)
    {
        return (substr($hora, 0, 5));
    }

    public static function pasarATiempo($delta)
    {
        $t = round($delta);
        return sprintf('%02d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60);
    }

    public static function construirExprFecha($inicio = null, $fin = null, $mes_largo = false, $Anyo = false)
    {
//        $inicio = '2014-06-23';
//        $fin = '2015-07-23';
        if ($mes_largo) {
            $meses = self::$_meses;
        } else {
            $meses = self::$_meses_corto;
        }

        $i = new DateTime($inicio);
        $f = new DateTime($fin);
        $mesInicio = $i->format('m');
        $mesFin = $f->format('m');

        $anoInicio = $i->format('Y');
        $anoFin = $f->format('Y');

        $msg = "Del {$i->format('j')} ";
        if ($mesInicio != $mesFin) {
            $msg .= "de " . $meses[$mesInicio - 1] . " ";
        }
        if ($anoInicio != $anoFin) {
            $msg .= "de {$anoInicio} ";
        }
        $msg .= "al {$f->format('j')} de " . $meses[$mesFin - 1] . " ";


        if ($inicio == $fin) { //poner sólo un día
            $msg = $f->format('j') . " de " . $meses[$mesFin - 1];
        }

        if ($Anyo) {
            $msg .= "de {$anoFin} ";
        }
        return $msg;
    }

    /**
     * Devuelve el lunes de la semana anterior
     * 
     * @return string
     */
    public static function lunesAnterior()
    {
        return date("Y-m-d", strtotime('monday last week'));
    }

}

//echo FechaHora::construirExprFecha('2014-06-23','2014-06-27');

//$delta = 120;
//echo FechaHora::pasarATiempo($delta);
//final de clase


//$fecha = date('Y-m-d');
//$dow = true;
//$year = true;
//echo FechaHora::Hoy();
//echo "\n";
//echo FechaHora::fechaATexto($fecha);
//echo "\n";
//echo FechaHora::fechaATexto($fecha, $dow);
//echo "\n";
//echo FechaHora::fechaATexto($fecha, $dow, $year);
//$dow = false;
//$year = false;
//echo "\n";
//echo FechaHora::fechaATexto($fecha, $dow, $year);
//echo fechaATexto(null);

//$dob = '1969-08-04';
//echo FechaHora::edad($dob);
//echo "\n";
//echo FechaHora::fechaCorta($dob);
//echo "\n";
//echo FechaHora::fechaCorta($dob, true);
//echo "\n";
//echo FechaHora::fechaCorta($dob, false, '');

//echo $fecha;
//$sep=' del ';
//echo FechaHora::fechaDMA($fecha, $sep);
//echo date('r');
//echo FechaHora::fechaCorta(date('Y-m-d')) ;