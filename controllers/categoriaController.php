<?php

class categoriaController extends Controller
{

    private $_k = 'categoria';
    private $_cmbCategorias;

    public function __construct()
    {
        $this->_modulo = $this->_k;
        parent::__construct();
        $this->_log->write(__METHOD__, LOG_DEBUG);
        $this->_model = $this->loadModel($this->_k);
        $this->_tabla = $this->_k;
        $this->_cmbCategorias = $this->_model->poblarComboCategoria();
    }

    public function index($pagina = false)
    {
        $this->_log->write(__METHOD__ . ' pagina=' . $pagina, LOG_DEBUG);
        Session::acceso('especial');

        $pagina = $this->getNumPagina($pagina);

        $this->_view->setJs(array('confirmarBorrar'));

        $this->getLibrary('paginador');
        $paginador = new Paginador();
        $data = $paginador->paginar($this->_model->getTablaCategorias(), $pagina);
        $this->ponerPaginacion($paginador, $this->_tabla);

        $prepararVista = array(
            'data' => $data,
            'columnas' => array('Nombre', 'Categoría Padre'),
            'cuenta' => $this->_model->getCount($this->_tabla),
        );
        $this->ponerBtnNuevo();

        $this->_prepararVista(__FUNCTION__, $prepararVista);
        $this->_view->renderizar('index', $this->_modulo);
    }

    public function ver($idCategoria, $pagina = false)
    {
        $this->_log->write(__METHOD__ . ' pagina=' . $pagina, LOG_DEBUG);

        $pagina = $this->getNumPagina($pagina);


        $this->getLibrary('paginador');
        $paginador = new Paginador();
        $campos = array('nombre', 'nombre_fichero', 'ancho_px');
        $data = $paginador->paginar($this->_model->getImagesByCategory($idCategoria), $pagina, IMAGENES_POR_PAGINA);

        $this->asignarMensajes();
//        $this->textos['tituloView']['ver'] = '';  
        $prepararVista = array(
            'data' => $data,
            "titulo" => "Categoría: " . capitalizar($this->_model->getCategoryName($idCategoria)),
            'columnas' => array('Nombre', 'Categoría Padre'),
            'cuenta' => $this->_model->getCount($this->_tabla),
        );
        $this->_prepararVista(__FUNCTION__, $prepararVista);
            $this->_view->assign('paginacion',
                    $paginador->getView('paginacion', $this->_modulo . '/ver/' . $idCategoria ));
//        vardumpy("FIN");
//        $this->_view->assign('titulo', "Imágenes de la categoría: " . $this->_model->getCategoryName($idCategoria));
        $this->_view->renderizar('ver', 'inicio');
    }

    public function editar($id)
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);
        Session::acceso('especial');

        if ($this->getInt('guardar') == 1) {
            $this->_view->assign('datos', $_POST); //TODO limpiar $_POST
            $this->_validar(ACCION_EDITAR);

            //guardar datos
            $campos = array(
                'nombre' => $this->getPostParam('nombre'),
                'descripcion' => $this->getPostParam('descripcion'),
            );

            if ($this->getInt('id_categoria') != 0) {//Si no tiene padre NO pasamos el 0
                $campos['id_categoria'] = $this->getInt('id_categoria');
            }

            $this->_model->editarRegistro($this->_tabla, $this->filtrarInt($id), $campos);

            Session::setMensaje('La categoría ha sido editada correctamente');

            $this->_log->write('EDITAR CATEGORIA id: ' . $id);
            $this->redireccionar($this->_modulo);
        }

        $prepararVista = array(
            'data' => $this->_model->getById($this->_tabla, $this->filtrarInt($id)),
        );

        $this->_prepararVista(__FUNCTION__, $prepararVista);
        $this->_view->renderizar('editar', $this->_modulo);
    }

    public function nuevo()
    {
        Session::acceso('especial');
        $this->_log->write(__METHOD__, LOG_DEBUG);

        $this->_view->setJs(array('validacion'));
        $this->_prepararVista(__FUNCTION__);

        if ($this->getInt('guardar') == 1) {
            $this->_view->assign('datos', $_POST); //TODO limpiar $_POST

            $this->_validar(ACCION_NUEVO);
            $campos = array(
                'nombre' => $this->getPostParam('nombre'),
                'descripcion' => $this->getPostParam('descripcion'),
            );
            if ($this->getInt('id_categoria') != 0) {//Si no tiene padre no pasamos el 0
                $campos['id_categoria'] = $this->getInt('id_categoria');
            }
            $this->_model->insertarRegistro($this->_tabla, $campos);


            Session::setMensaje('La categoría ha sido creada correctamente');
            $this->_log->write('NUEVA CATEGORIA: ' . array_to_str($campos), LOG_NOTICE);
            $this->redireccionar($this->_modulo);
        }

        $this->_view->renderizar('nuevo', $this->_modulo);
    }

    public function eliminar($id)
    {
        $id = $this->filtrarInt($id);
        $this->_log->write(__METHOD__, LOG_DEBUG);

        if (!($data = $this->_model->getById($this->_tabla, $id))) {
            Session::setError('La categoría con id #' . $id . ' no existe');
            $this->redireccionar($this->_modulo);
        }

        if ($this->_model->eliminarRegistro($this->_tabla, $id)) {
            Session::setMensaje(
                    "La categoría # " . $data['id'] . " - <strong>" . $data['nombre'] . "</strong>"
                    . " ha sido borrada correctamente");

            $this->_log->write('BORRAR CATEGORIA -' . $data['id'] . " - " . $data['nombre'],
                    LOG_NOTICE);
        }
        else {
            Session::setError('Se ha producido un error al borrar la categoría <strong>"'
                    . $data['nombre'] . '"</strong>');
            $this->_log->write('ERROR AL BORRAR CATEGORIA -' . $data['id'] . " - " . $data['nombre']);
        }

        $this->redireccionar($this->_modulo . '/index/' . Session::get('pagina'));
    }

    public function crearAutomatico()
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);
        $this->_model->crear(50);

        $this->redireccionar($this->_modulo);
    }

    private function _validar($accion)
    {
        $this->_log->write(__METHOD__ . ' ' . $accion, LOG_DEBUG);

        $error = false;
        $mensaje = null;

        if (!$this->getPostParam('nombre')) {
            $mensaje = 'Debe introducir un nombre de categoría';
            $error = true;
        }

        if ($error) {
            $this->_view->assign('_error', $mensaje);
            $this->_view->assign('data', $_POST);
            $this->_view->renderizar($accion, $this->_modulo);
            exit;
        }
    }

    protected function _prepararVista($metodo, $preparaVista = array())
    {
        parent::_prepararVista($metodo, $preparaVista);
        $this->_view->assign('categorias', $this->_cmbCategorias);
    }

}
