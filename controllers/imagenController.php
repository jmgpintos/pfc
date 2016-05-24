<?php

class imagenController extends Controller
{

    private $_k = 'imagen';

    public function __construct()
    {
        parent::__construct();
        $this->_model = $this->loadModel($this->_k);
        $this->_table = $this->_k;
        $this->_modulo = $this->_k;
    }

    public function index($pagina=false)
    {
        $this->_log->write(__METHOD__ . ' pagina=' . $pagina, LOG_DEBUG);

        $pagina = $this->getNumPagina($pagina);

        $this->asignarMensajes();
        
        $this->getLibrary('paginador');
        $paginador = new Paginador();
        $data = $paginador->paginar($this->_model->getAll($this->_tabla), $pagina);
        
        $this->_view->assign('data', $data);
        $this->_view->assign('paginacion',
                $paginador->getView('paginacion', $this->_modulo . '/index'));
        $this->_view->assign('columnas', $this->_model->getColumnas($data));
        $this->_view->assign('cuenta', $this->_model->getCount($this->_table));
        $this->_view->assign('titulo', 'Imágenes');
        $this->_view->assign('tituloView', 'Lista de imágenes');
        $this->_view->renderizar('index', $this->_modulo);
        
    }

}
