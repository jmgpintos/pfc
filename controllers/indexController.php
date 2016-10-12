<?php

class indexController extends Controller
{

    private $_k = 'imagen';

    public function __construct()
    {
        $this->_modulo = 'index';
        parent::__construct();

        $this->_model = $this->loadModel($this->_k);
        $this->_tabla = $this->_k;

        $this->textos['tituloView']['index'] = 'Lista de imágenes';
        $this->textos['titulo'] = 'Imágenes';
    }

    public function index($pagina = false)
    {

        $model = $this->loadModel('imagen');
        $data = $this->_model->getAll();

        $this->getLibrary('paginador');
        $paginador = new Paginador();
        $campos = array('nombre', 'nombre_fichero', 'ancho_px');
        $data = $paginador->paginar($this->_model->getAllFrontPage(), $pagina, 12);
        $data = $this->_model->getImageForFrontPage();
        
        $fecha = new FechaHora();
        $data['fecha_creacion'] = $fecha->fechaLarga($data['fecha_creacion']);        

        $this->asignarMensajes();
        $this->_view->assign('data', $data);
        $this->_view->assign('titulo', "Imagen del día");
        $this->_view->renderizar('index', 'inicio');
    }

}
