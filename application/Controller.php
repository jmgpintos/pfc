<?php

/*
 * Clase Controller, de la que heredan todos los controladores
 * 
 * Métodos para tratar datos y comunicar vistas con modelos
 * 
 */

abstract class Controller
{

    /**
     *
     * @var Modelo modelo utilizado por el controlador
     */
    protected $_model;

    /**
     *
     * @var string nombre del modulo/controlador
     */
    protected $_modulo;

    /**
     *
     * @var string Nombre de la tabla utilizada por el controlador
     */
    protected $_tabla;

    /**
     *
     * @var Vista (Smarty) Vista utilizada por el controlador
     */
    protected $_view;

    /**
     *
     * @var log Objeto log que usará el controlador para escribir
     */
    protected $_log;

    /**
     *
     * @var array textos que se envían a la vista
     */
    protected $textos = array();

    /**
     * Constructor por defecto
     * instancia un objeto vista (Smarty) al que se le mandarán los datos
     * instancia un objeto para poder escribir en el archivo log
     * Define los textos que se envían a ls vistas y que son comunes a todas ellas. Se pueden sobreescribir en cada vista
     * actualiza la variable de sesión 'tiempo', para mantener activa la sesión del usuario
     */
    public function __construct()
    {
        $this->_view = new View(new Request);
        $this->_log = new Log();
        $this->textos = array(
            'titulo' => capitalizar($this->_modulo . 's'),
            'tituloView' => array(
                'index' => 'Lista de ' . $this->_modulo . 's',
                'nuevo' => 'Nueva ' . $this->_modulo,
                'editar' => 'Editar ' . $this->_modulo,
                'eliminar' => 'Borrar ' . $this->_modulo,
            ),
            'nuevo' => "Agregar " . $this->_modulo,
        );
        Session::tiempo(); //Actualiza tiempo de sesión cada vez que entra en una página
    }

    /**
     * Todos los objetos controlador deben tener 
     * un objeto index (el método por defecto DEFAULT_METHOD)
     */
    abstract public function index();

    /**
     * Carga el modelo adecuado para el controlador
     * 
     * @param string $modelo Nombre del modelo a cargar
     * @return \modelo
     * @throws Exception
     */
    protected function loadModel($modelo)
    {
        $this->_log->write(__METHOD__
                . ' - modelo => ' . $modelo
                );
        
        $modelo = $modelo . 'Model';
        $rutaModelo = ROOT . 'models' . DS . $modelo . '.php';

        if (is_readable($rutaModelo)) {
            require_once $rutaModelo;
            $modelo = new $modelo;
            return $modelo;
        }
        else {
            throw new Exception("Error de modelo");
        }
    }

    /**
     * Carga una libreria
     * 
     * @param type $libreria Nombre de la librería
     * @throws Exception
     */
    protected function getLibrary($libreria)
    {
        $rutaLibreria = ROOT . 'libs' . DS . $libreria . '.php';
        if (is_readable($rutaLibreria)) {
            require_once $rutaLibreria;
        }
        else {
            throw new Exception("Error al cargar libreria $libreria");
        }
    }

    /**
     * Lee y limpia un valor enviado por POST
     * 
     * @param string $clave Nombre de la clave -> $_POST[$clave]
     * @return string
     */
    protected function getTexto($clave)
    {
        //No utilizar con prepare para insertar datos
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = htmlspecialchars($_POST[$clave], ENT_QUOTES); //TODO explicar
            return $_POST[$clave];
        }

        return '';
    }

    /**
     * Lee y convierte en entero un valor enviado por POST
     * 
     * @param string $clave Nombre de la clave -> $_POST[$clave]
     * @return int
     */
    protected function getInt($clave)
    {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT); //TODO explicar
            return $_POST[$clave];
        }

        return 0;
    }

    /**
     * Redirecciona a la ruta indicada (página principal si $ruta==false)
     * 
     * @param string $ruta
     */
    protected function redireccionar($ruta = false)
    {
        if ($ruta) {
            header('location:' . BASE_URL . $ruta);
            exit;
        }
        else {
            header('location:' . BASE_URL);
            exit;
        }
    }

    /**
     * Convierte un valor a entero
     * 
     * @param string $int
     * @return int
     */
    protected function filtrarInt($int)
    {
        $int = (int) $int;

        if (is_int($int)) {
            return $int;
        }
        else {
            return 0;
        }
    }

    /**
     * Devuelve un valor pasado por POST
     * 
     * @param string $clave Nombre de la clave -> $_POST[$clave]
     * @return string
     */
    protected function getPostParam($clave)
    {
        if (isset($_POST[$clave])) {
            return $_POST[$clave];
        }
    }

    /**
     * Sanitizar cadenas SQL
     * @param string $clave Nombre de la clave del array $_POST a sanitizar
     * @return string Cadena sanitizada
     */
    protected function getSql($clave)
    {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = strip_tags($_POST[$clave]);

            if (!get_magic_quotes_gpc()) {
                $_POST[$clave] = addslashes($_POST[$clave]);
            }
//            puty($_POST[$clave]);
            return trim($_POST[$clave]);
        }
    }

    /**
     * Sanitizar cadenas alfanuméricas
     * Sólo acepta caracteres alfabéticos, numéricos y guión bajo
     * @param string $clave Nombre de la clave del array $_POST a sanitizar
     * @return string Cadena sanitizada
     */
    protected function getAlphaNum($clave)
    {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = (string) preg_replace('/[^A-Z0-9_]/i', '', $_POST[$clave]);
            return trim($_POST[$clave]);
        }
    }

    public function validarEmail($email)
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    /**
     * Trata el parámetro $pagina
     * @param string $pagina
     * @return int|boolean
     */
    public function getNumPagina($pagina)
    {
        if (!$this->filtrarInt($pagina)) {
            $pagina = false;
        }
        else {
            $pagina = (int) $pagina;
        }

        Session::set('pagina', $pagina);
        return $pagina;
    }

    /**
     * Pasa los mensajes de sesión a la vista
     */
    public function asignarMensajes()
    {
        if (Session::getMensaje()) {
            $this->_view->assign('_mensaje', Session::getMensaje());
            Session::destroy('mensaje');
        }
        if (Session::getError()) {
            $this->_view->assign('_error', Session::getError());
            Session::destroy('error');
        }

        $this->_view->assign('_pagina', Session::get('pagina'));
    }

    /**
     * Determina si hay que poner la zona de paginación 
     * (cuando los registros devueltos > REGISTROS_POR_PAGINA)
     * 
     * @param Paginador $paginador
     * @param string $tabla
     * @param int $registros registros a mostrar en cada página
     */
    protected function ponerPaginacion($paginador, $tabla, $registros = REGISTROS_POR_PAGINA)
    {
        if ($this->_model->getCount($tabla) > $registros) {
            $this->_view->assign('paginacion',
                    $paginador->getView('paginacion', $this->_modulo . '/index'));
        }
    }

    /**
     * Devuelve array para colocar en combo
     * 
     * @param string $tabla Tabla de la que se leen los datos
     * @param string $primeraOpcion Opción para el primer valor del combo (devuelve valor nulo)
     * 
     * @return array
     */
    protected function pasarTablaACombo($tabla, $primeraOpcion = '', $hayPrimeraOpcion = TRUE)
    {
        $r = $this->_model->getTabla($tabla, 'nombre');
        if ($hayPrimeraOpcion) {
            array_unshift($r,
                    array(
                'id' => '0',
                'nombre' => $primeraOpcion));
        }
        return $r;
    }

    /**
     * Devuelve array para colocar en combo
     * 
     * @param string $array Array del que se leen los datos
     * @param string $primeraOpcion Opción para el primer valor del combo (devuelve valor nulo)
     * 
     * @return array
     */
    protected function pasarArrayACombo($array, $primeraOpcion = '', $hayPrimeraOpcion = TRUE)
    {
        $r = $array;
        if ($hayPrimeraOpcion) {
            array_unshift($r,
                    array(
                'id' => '0',
                'nombre' => $primeraOpcion));
        }
        return $r;
    }

    /**
     * Pasa a la vista el texto (attribute title) para el botón 'Nuevo registro'
     * 
     * @param string $msg
     */
    protected function ponerBtnNuevo($msg = null)
    {
        if (null == $msg) {
            $msg = $this->textos['nuevo'];
        }
        $this->_view->assign('ponerBtnNuevo', $msg);
    }

    /**
     * Envía textos a la vista
     * 
     * @param String $metodo Método que llama a esta función ('index'|'editar'|'nuevo'), 
     * para poner el título adecuado en la vista
     */
    protected function _prepararVista($metodo, $preparaVista = array())
    {
//        vardumpy($this->textos);
        $this->asignarMensajes();
        $this->_view->assign('titulo', $this->textos['titulo']);
        if(isset($this->textos['tituloView'][$metodo])){
            $this->_view->assign('tituloView', $this->textos['tituloView'][$metodo]);
        }
        $this->_view->assign('tituloEliminar', $this->textos['tituloView']['eliminar']);
        $this->_view->assign('controlador', $this->_modulo . '/');

        foreach ($preparaVista as $key => $value) {
//            put("XXXXXXXXXx");
            $this->_view->assign($key, $value);
        }
    }
    

}
