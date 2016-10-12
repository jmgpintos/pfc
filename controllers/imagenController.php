<?php

class imagenController extends Controller
{

    private $_k = 'imagen';

    public function __construct()
    {
        $this->_modulo = $this->_k;
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
        $data = $paginador->paginar($this->_model->getAll(), $pagina, IMAGENES_POR_PAGINA);
//        $data = $paginador->paginar($this->_model->getByCategory(10), $pagina, 12);
//        vardump($data);
        for ($i = 0; $i < count($data); $i++){
            $fecha = new FechaHora();
            $data[$i]['fecha_creacion'] = $fecha->fechaCorta($data[$i]['fecha_creacion']);
        }
//        vardumpy($data);
//        Session::setMensaje("Mensaje de prueba");
        
        $this->asignarMensajes();
        $this->_view->assign('data', $data);
            $this->_view->assign('paginacion',
                    $paginador->getView('paginacion', $this->_modulo . '/index'));
//        $this->_view->assign('titulo', "Portada");
        $this->_view->renderizar('index', 'inicio');
        
        /*
        Session::set('estilo_vista_imagen', $estilo);
        $pagina = $this->getNumPagina($pagina);
        $registrosPorPagina = array(
            'list' => REGISTROS_POR_PAGINA_LIST,
            'table' => REGISTROS_POR_PAGINA_TABLE,
            'card' => REGISTROS_POR_PAGINA_CARD,
        );
        $this->_log->write(__METHOD__ . ' pagina=' . $pagina, LOG_DEBUG);

        $preparaVista = array(
            'thumb_prefix' => THUMBNAIL_FILE_PREFIX,
            'mid_prefix' => MID_FILE_PREFIX,
            'thumbs_dir' => THUMBNAIL_DIR,
            'mids_dir' => MID_DIR
        );

        $this->_view->setJs(array('confirmarBorrar'));
        $this->_prepararVista(__FUNCTION__, $preparaVista);


        $this->getLibrary('paginador');
        $paginador = new Paginador();
        $campos = array('nombre', 'nombre_fichero', 'ancho_px');
        $data = $paginador->paginar($this->_model->getAll(), $pagina, $registrosPorPagina[$estilo]);

        $this->_view->assign('data', $data);
        $this->_view->assign('estilo', $estilo);
        if ($this->_model->getCount($this->_tabla) > $registrosPorPagina[$estilo]) {
            $this->_view->assign('paginacion',
                    $paginador->getView('paginacion', $this->_modulo . '/index/' . $estilo));
        }
        $this->_view->assign('columnas',
                array('', 'nombre', 'dimensiones', 'Tamaño', 'categoría', 'licencia'));
        $this->_view->assign('cuenta', $this->_model->getCount($this->_tabla));
        $this->ponerBtnNuevo();
        $this->_view->assign('controlador', $this->_modulo . '/');

        $this->_view->renderizar($estilo, $this->_modulo);
         * 
         */
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

    public function editar($idImagen)
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);
        Session::acceso('especial');

        $data = $this->_model->getById($this->_tabla, $idImagen)[0];

//        vardump($data);
        
        $modelCategoria = $this->loadModel('categoria');        
        $cmbCategorias = $modelCategoria->poblarComboCategoria();
        
//        vardumpy($cmbCategorias);
        $preparaVista =  $this->_getPaginacion($data);
        $this->_prepararVista(__FUNCTION__, $preparaVista);
        
        $this->_view->assign('cmbCategorias', $cmbCategorias);

        $this->_view->assign('data', $data);

        $this->_view->renderizar('editar', $this->_modulo);
    }

    public function ver($idImagen)
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);
        
        $this->getLibrary('navegador');
        $navegador = new Navegador($this->_tabla, $idImagen);
        $nav = $navegador->navegar();
//        vardumpy($nav);

        $data = $this->_model->getById($this->_tabla, $idImagen)[0];

//        vardump($data);


        $preparaVista =  $this->_getPaginacion($data);
//vardumpy($preparaVista);
        $this->_prepararVista(__FUNCTION__, $preparaVista);

        $this->_view->assign('data', $data);
        $this->_view->assign('navegacion', $nav);

        $this->_view->renderizar('ver', $this->_modulo);
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
//puty($this->_modulo . '/'. Session::get('estilo_vista_imagen'));
        $this->redireccionar($this->_modulo . '/index/' . Session::get('estilo_vista_imagen'));
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
            $this->_crearMedio($upload);
            $this->_crearMiniatura($upload);
        }
        else {
            $this->_view->assign('_error', $upload->error);
            return false;
        }
        return $upload;
    }

    private function _crearMiniatura($upload)
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

    private function _crearMedio($upload)
    {
        $mid = new upload($upload->file_dst_pathname);
        $mid->image_resize = true;
        $mid->image_ratio = true;
        $mid->image_y = MID_SHORT_SIDE;
        $mid->image_x = MID_LONG_SIDE;


        $mid->image_resize = true;
//        if ($this->esHorizontal($thumb)) {
//            $thumb->image_x = THUMBNAIL_LONG_SIDE;
//            $thumb->image_y = THUMBNAIL_SHORT_SIDE;
//        }
//        else {
//            $thumb->image_x = THUMBNAIL_SHORT_SIDE;
//            $thumb->image_y = THUMBNAIL_LONG_SIDE;
//        }
        $mid->file_name_body_pre = MID_FILE_PREFIX;
        $mid->process(IMAGE_FILE_PATH . 'mids' . DS);
    }

    private function esHorizontal($img)
    {
        return $img->image_src_x > $img->image_src_y;
    }

    private function _getPaginacion($data)
    {
        $paginacion = array(
            'primero' => array(
                'num' => ($data["id"] == $this->_model->primero($this->_tabla)) ? "" : $this->_model->primero($this->_tabla),
                'estilo' => ($data["id"] == $this->_model->primero($this->_tabla)) ? "disabled" : "",
            ),
            'anterior' => array(
                'num' => ($data["id"] == $this->_model->primero($this->_tabla)) ? "" : $data['id'] - 1,
                'estilo' => ($data["id"] == $this->_model->primero($this->_tabla)) ? "disabled" : "",
            ),
            'siguiente' => array(
                'num' => ($data["id"] == $this->_model->ultimo($this->_tabla)) ? "" : $data['id'] + 1,
                'estilo' => ($data["id"] == $this->_model->ultimo($this->_tabla)) ? "disabled" : "",
            ),
            'ultimo' => array(
                'num' => ($data["id"] == $this->_model->ultimo($this->_tabla)) ? "" : $this->_model->ultimo($this->_tabla),
                'estilo' => ($data["id"] == $this->_model->ultimo($this->_tabla)) ? "disabled" : "",
            ),
        );
        
        return $paginacion;
    }

}
