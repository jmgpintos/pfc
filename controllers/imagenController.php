<?php

class imagenController extends Controller
{

    private $_nombreModulo = 'imagen';

    public function __construct()
    {
        parent::__construct();
        $this->_model = $this->loadModel($this->_nombreModulo);
        $this->_tabla = $this->_nombreModulo;
        $this->_modulo = $this->_nombreModulo;
    }

    public function index($estilo ='card', $pagina = false)
    {
        $registrosPorPagina = array(
            'list' =>REGISTROS_POR_PAGINA_LIST,
            'table' =>REGISTROS_POR_PAGINA_TABLE,
            'card' =>REGISTROS_POR_PAGINA_CARD,
        );
        $this->_log->write(__METHOD__ . ' pagina=' . $pagina, LOG_DEBUG);

        $pagina = $this->getNumPagina($pagina);

        $this->asignarMensajes();

        $this->getLibrary('paginador');
        $paginador = new Paginador();
        $campos = array('nombre', 'nombre_fichero', 'ancho_px');
        $data = $paginador->paginar($this->_model->getAll(), $pagina, $registrosPorPagina[$estilo]);

        $this->_view->assign('data', $data);
        $this->_view->assign('estilo', $estilo);
        if ($this->_model->getCount($this->_tabla) > $registrosPorPagina[$estilo]) {
        $this->_view->assign('paginacion',
                $paginador->getView('paginacion', $this->_modulo . '/index/'. $estilo));
        }
        $this->_view->assign('columnas', array('','nombre', 'dimensiones', 'Tamaño', 'categoría', 'licencia'));
        $this->_view->assign('cuenta', $this->_model->getCount($this->_tabla));
        $this->_view->assign('titulo', 'Imágenes');
        $this->_view->assign('tituloView', 'Lista de imágenes');
        $this->_view->assign('controlador', $this->_modulo. '/');
        
        $this->_view->renderizar($estilo, $this->_modulo);
    }

    public function nuevo()
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);
        Session::acceso('especial');
        $this->_view->assign('titulo', "Nueva imagen");
//        $this->_view->setJs(array('nuevo'));

        if ($this->getInt('guardar') == 1) {
            $this->_view->assign('datos', $_POST); //TODO limpiar $_POST

            $this->validar();

            if (isset($_FILES['imagen']['name'])) { //Si se envió un archivo lo procesamos
                if (!($imagen = $this->procesarImagen($_FILES['imagen']))) {
                    $this->_view->renderizar('nuevo', $this->_modulo);
                    exit;
                }
            }

            //guardar datos
            $this->_model->insertarImagen(
                    $this->getPostParam('nombre'), $imagen
            );

            Session::setMensaje('La imagen ha sido creado correctamente');
            $this->_log->write('NUEVA IMAGEN');
            //TODO enviar a editar, para comprobar datos
            $this->redireccionar($this->_modulo);
        }

        $this->_view->renderizar('nuevo', $this->_modulo);
    }

    public function eliminar($id)
    {
        if (!($data = $this->_model->getById($this->_tabla, $id))) {
            Session::setError('La imagen con id #' . $id . ' no existe');
            $this->redireccionar($this->_modulo);
        }

        if ($this->_model->eliminarRegistro($this->_tabla, $id)) {
            Session::setMensaje(
                    "La imagen # " . $data['id'] . " - <strong>" . $data['nombre'] . "</strong>"
                    . " correspondiente al archivo <i>" . $data['nombre_fichero'] . "</i>"
                    . " ha sido borrada correctamente");

            $this->_log->write('BORRAR IMAGEN -' . $data['id'] . " - " . $data['nombre'], LOG_NOTICE);
        }
        else {
            Session::setError('Se ha producido un error al borrar el registro');
            $this->_log->write('ERROR AL BORRAR IMAGEN -' . $data['id'] . " - " . $data['nombre']);
        }

        $this->redireccionar($this->_modulo. '/list');
    }

    private function validar()
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);
        if (!$this->getTexto('nombre')) {
            $this->_view->assign('_error', 'Debe introducir el nombre de la imagen');
            $this->_view->renderizar('editar', $this->_modulo);
            exit;
        }
    }

    private function procesarImagen($img)
    {
        $this->getLibrary('upload' . DS . 'class.upload');
        $ruta = IMAGE_FILE_PATH;
        $upload = new upload($img, 'es_ES');
        $upload->allowed = array('image/*');
        $upload->file_new_name_body = IMAGE_FILE_PREFIX . uniqid();
        $upload->process($ruta);

        if ($upload->processed) {//Si se ha procesado sin problemas creamos el thumbnail
            $this->crearMiniatura($upload);
        }
        else {
            $this->_view->assign('_error', $upload->error);
            return false;
        }
        return $upload;
    }

    private function crearMiniatura($upload)
    {
        $thumb = new upload($upload->file_dst_pathname);
        $thumb->image_resize = true;
        if ($this->esHorizontal($thumb)) {
            $thumb->image_x = THUMBNAIL_LONG_SIDE;
            $thumb->image_y = THUMBNAIL_SHORT_SIDE;
        }
        else {
            $thumb->image_x = THUMBNAIL_SHORT_SIDE;
            $thumb->image_y = THUMBNAIL_LONG_SIDE;
        }
        $thumb->file_name_body_pre = THUMBNAIL_FILE_PREFIX;
        $thumb->process(IMAGE_FILE_PATH . 'thumbs' . DS);
    }

    private function esHorizontal($img)
    {
        return $img->image_src_x > $img->image_src_y;
    }

}
