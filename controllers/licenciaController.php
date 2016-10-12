<?php

class licenciaController extends Controller
{

    private $_k = 'licencia';

    public function __construct()
    {
        $this->_modulo = $this->_k;
        parent::__construct();
        $this->_model = $this->loadModel($this->_k);
        $this->_tabla = $this->_k;
    }

    public function index($pagina = false)
    {
        Session::acceso('especial');
        $this->_log->write(__METHOD__ . ' pagina=' . $pagina, LOG_DEBUG);

        $pagina = $this->getNumPagina($pagina);


        $this->getLibrary('paginador');
        $paginador = new Paginador();
        $campos = array('nombre', 'descripcion');
        $data = $paginador->paginar($this->_model->getAll($this->_tabla, $campos), $pagina);

        $this->ponerPaginacion($paginador, $this->_tabla);
        $this->ponerBtnNuevo();

        $prepararVista = array(
            'data' => $data,
            'columnas' => $this->_model->getColumnas($data),
            'cuenta' => $this->_model->getCount($this->_tabla),
        );
        $this->_prepararVista(__FUNCTION__, $prepararVista);
        $this->_view->renderizar('index', $this->_modulo);
    }

    public function editar($id)
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);


        if ($this->getInt('guardar') == 1) {
//            puty($this->getPostParam('nombre'));
//            vardumpy($_POST);
            $this->_view->assign('datos', $_POST); //TODO limpiar $_POST
            $this->_validar(ACCION_EDITAR);

            //guardar datos
            $campos = array(
                'nombre' => $this->getPostParam('nombre'),
                'descripcion' => $this->getPostParam('descripcion'),
            );

            $this->_model->editarRegistro($this->_tabla, $this->filtrarInt($id), $campos);

            Session::setMensaje('La licencia ha sido editada correctamente');

            $this->_log->write('EDITAR LICENCIA id: ' . $id);
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
        $this->_log->write(__METHOD__, LOG_DEBUG);
        $this->_prepararVista(__FUNCTION__);
        Session::acceso('especial');
        $this->_view->setJs(array('validacion'));

//
        if ($this->getInt('guardar') == 1) {
            $this->_view->assign('datos', $_POST); //TODO limpiar $_POST

            $this->_validar(ACCION_NUEVO);
            $campos = array(
                'nombre' => $this->getPostParam('nombre'),
                'descripcion' => $this->getPostParam('descripcion'),
            );

            $this->_model->insertarRegistro($this->_tabla, $campos);


            Session::setMensaje('La licencia ha sido creada correctamente');
            $this->_log->write('NUEVA LICENCIA: ' . array_to_str($campos), LOG_NOTICE);
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

    private function _validar($accion)
    {
        $this->_log->write(__METHOD__ . ' ' . $accion, LOG_DEBUG);

        $error = false;
        $mensaje = null;

        if (!$this->getPostParam('nombre')) {
            $mensaje = 'Debe introducir un nombre de licencia';
            $error = true;
        }

        if ($error) {
            $this->_view->assign('_error', $mensaje);
            $this->_view->assign('data', $_POST);
            $this->_view->renderizar($accion, $this->_modulo);
            exit;
        }
    }

}
