<?php

class categoriaController extends Controller
{

    private $_k = 'categoria';
    private $_cmbCategorias;

    public function __construct()
    {
        parent::__construct();
        $this->_log->write(__METHOD__, LOG_DEBUG);
        $this->_model = $this->loadModel($this->_k);
        $this->_tabla = $this->_k;
        $this->_modulo = $this->_k;
        $this->_cmbCategorias = $this->_model->poblarComboCategoria();
    }

    public function index($pagina = false)
    {
        $this->_log->write(__METHOD__ . ' pagina=' . $pagina, LOG_DEBUG);

        $pagina = $this->getNumPagina($pagina);

        $this->_view->setJs(array('confirmarBorrar'));


        $this->_prepararVista();

        $this->getLibrary('paginador');
        $paginador = new Paginador();
//        $campos = array('nombre', 'descripcion', 'id_categoria');
        $data = $paginador->paginar($this->_model->getTablaCategorias(), $pagina);

        $this->_view->assign('data', $data);
        $this->ponerPaginacion($paginador, $this->_tabla);
        $this->_view->assign('columnas', array('Nombre', 'Categoría Padre'));
        $this->_view->assign('cuenta', $this->_model->getCount($this->_tabla));
        $this->_view->assign('tituloView', 'Lista de categorías');

        $this->_view->renderizar('index', $this->_modulo);
    }

    public function editar($id)
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);

        $this->_prepararVista();

        $this->_view->assign('tituloView', 'Editar categoría');

        if ($this->getInt('guardar') == 1) {
            $this->_view->assign('datos', $_POST); //TODO limpiar $_POST
            $this->validar(ACCION_EDITAR);

            //guardar datos
            $campos = array(
                'nombre' => $this->getAlphaNum('nombre'),
                'descripcion' => $this->getPostParam('descripcion'),
            );
            if ($this->getInt('id_categoria') != 0) {//Si no tiene padre no pasamos el 0
                $campos['id_categoria'] = $this->getInt('id_categoria');
            }

            $this->_model->editarRegistro($this->_tabla, $this->filtrarInt($id), $campos);

            Session::setMensaje('La categoría ha sido editada correctamente');

            $this->_log->write('EDITAR CATEGORIA id: ' . $id);
            $this->redireccionar($this->_modulo);
        }

        $data = $this->_model->getById($this->_tabla, $this->filtrarInt($id));
        $this->_view->assign('data', $data);
//        vardumpy($this->_cmbCategorias);

        $this->_view->renderizar('editar', $this->_modulo);
    }

    public function nuevo()
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);
        $this->_prepararVista();
        Session::acceso('especial');
        $this->_view->assign('tituloView', "Nueva categoría");
        $this->_view->setJs(array('validacion'));

//
        if ($this->getInt('guardar') == 1) {
            $this->_view->assign('datos', $_POST); //TODO limpiar $_POST

            $this->validar(ACCION_NUEVO);
            $campos = array(
                'nombre' => $this->getAlphaNum('nombre'),
                'descripcion' => $this->getPostParam('descripcion'),
            );
            if ($this->getInt('id_categoria') != 0) {//Si no tiene padre no pasamos el 0
                $campos['id_categoria'] = $this->getInt('id_categoria');
            }
            $this->_model->insertarRegistro($this->_tabla, $campos);


            Session::setMensaje('La categoría ha sido creada correctamente');
            $this->_log->write('NUEVA CATEGORIA', LOG_NOTICE);
            $this->redireccionar($this->_modulo);
        }

        $this->_view->assign('categorias', $this->_cmbCategorias);
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

    private function validar($accion)
    {
        $this->_log->write(__METHOD__ . ' ' . $accion, LOG_DEBUG);

        $error = false;
        $mensaje = null;

        if (!$this->getAlphaNum('nombre')) {
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

    private function _prepararVista()
    {
        $this->asignarMensajes();
        $this->_view->assign('titulo', 'Categorías');
        $this->_view->assign('categorias', $this->_cmbCategorias);
        $this->_view->assign('controlador', $this->_modulo . '/');
    }

}
