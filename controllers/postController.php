<?php

class postController extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->_model = $this->loadModel('post');
        $this->_table = 'posts';
        $this->_modulo = 'post';
    }

    public function index($pagina = false)
    {
        $this->_log->write(__METHOD__,LOG_DEBUG);
//        puty($pagina);
        if (!$this->filtrarInt($pagina)) {
            $pagina = false;
        }
        else {
            $pagina = (int) $pagina;
        }

        $this->asignarMensajes();

        $this->getLibrary('paginador');
        $paginador = new Paginador();
        $posts = $paginador->paginar($this->_model->getPosts(), $pagina);

        $this->_view->assign('posts', $posts);
        $this->_view->assign('paginacion',
                $paginador->getView('paginacion', $this->_modulo . '/index'));
        $this->_view->assign('columnas', $this->_model->getColumnas($posts));
        $this->_view->assign('cuenta', $this->_model->getCount($this->_table));
        $this->_view->assign('titulo', 'Post');
        $this->_view->assign('tituloView', 'Últimos Posts');
        $this->_view->renderizar('index', $this->_modulo);
    }

    public function nuevo()
    {
        $this->_log->write(__METHOD__,LOG_DEBUG);
        Session::acceso('especial');
        $this->_view->assign('titulo', "Nuevo post");
        $this->_view->setJs(array('nuevo'));

        //
        if ($this->getInt('guardar') == 1) {
            $this->_view->assign('datos', $_POST); //TODO limpiar $_POST

            $this->validar();

            //guardar datos
            $this->_model->insertarPost(
                    $this->getPostParam('titulo'), $this->getPostParam('cuerpo')
            );

            Session::setMensaje('El Post ha sido creado correctamente');
            $this->_log->write('NUEVO POST');
            $this->redireccionar($this->_modulo);
        }

        $this->_view->renderizar('nuevo', $this->_modulo);
    }

    public function editar($id)
    {
        $this->_log->write(__METHOD__,LOG_DEBUG);
        if (!$this->existe($id)) {
            Session::setError('El Post no existe');
            $this->redireccionar($this->_modulo);
        }

        $this->_view->assign('titulo', "Editar post");
        $this->_view->setJs(array('nuevo'));

        if ($this->getInt('guardar') == 1) {
            $this->_view->assign('datos', $_POST); //TODO limpiar $_POST

            $this->validar();

            //guardar datos
            $this->_model->editarPost(
                    $this->filtrarInt($id), $this->getPostParam('titulo'),
                    $this->getPostParam('cuerpo')
            );

            Session::setMensaje('El Post ha sido editado correctamente');
            
            $this->_log->write('EDITAR POST');
            $this->redireccionar($this->_modulo);
        }

        $this->_view->assign('datos', $this->_model->getPost($this->filtrarInt($id)));
        $this->_view->renderizar('editar', $this->_modulo);
    }

    public function eliminar($id)
    {
        $this->_log->write(__METHOD__,LOG_DEBUG);
        if (!$this->existe($id)) {
            Session::setErro('El Post no existe');
            $this->redireccionar($this->_modulo);
        }

        $post = $this->_model->getById('posts', $id);

        $this->_model->eliminarPost($this->filtrarInt($id));

        if (!$this->_model->getById('posts', $id)) {
            Session::setMensaje(
                    "El Post # " . $post['id'] . " - " . $post['titulo']
                    . " ha sido borrado correctamente");
            
            $this->_log->write('BORRAR POST -' . $post['id'] . " - " . $post['titulo'], LOG_NOTICE);
        }
        else {
            Session::setError('Se ha producido un error al borrar el registro');
            $this->_log->write('ERROR AL BORRAR POST -' . $post['id'] . " - " . $post['titulo']);
        }

        $this->redireccionar($this->_modulo);
    }

    public function existe($id)
    {
        $this->_log->write(__METHOD__,LOG_DEBUG);
        return ($this->filtrarInt($id) && $this->_model->getPost($this->filtrarInt($id)));
    }

    private function validar()
    {
        $this->_log->write(__METHOD__,LOG_DEBUG);
        if (!$this->getTexto('titulo')) {
            $this->_view->assign('_error', 'Debe introducir el título del post');
            $this->_view->renderizar('editar', $this->_modulo);
            exit;
        }
        if (!$this->getTexto('cuerpo')) {
            $this->_view->assign('_error', 'Debe introducir el cuerpo del post');
            $this->_view->renderizar('editar', $this->_modulo);
            exit;
        }
    }

    public function crear100()
    {
        $this->_log->write(__METHOD__,LOG_DEBUG);
        $this->_model->crear100();

        $this->redireccionar($this->_modulo);
    }

    public function borrarPruebas()
    {
        $this->_log->write(__METHOD__,LOG_DEBUG);
        $this->_model->borrarPruebas($this->_tabla, 9);

        $this->redireccionar($this->_modulo);
    }

}
