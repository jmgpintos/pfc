<?php

class postController extends Controller
{

    private $_post;

    function __construct()
    {
        parent::__construct();
        $this->_post = $this->loadModel('post');
    }

    public function index($pagina = false)
    {
//        puty($pagina);
        if (!$this->filtrarInt($pagina)) {
            $pagina = false;
        }
        else {
            $pagina = (int) $pagina;
        }

        $this->getLibrary('paginador');
        $paginador = new Paginador();
        $posts = $paginador->paginar($this->_post->getPosts(), $pagina);

        $this->_view->assign('posts', $posts);
        $this->_view->assign('paginacion', $paginador->getView('paginacion', 'post/index'));
        $this->_view->assign('columnas', $this->_post->getColumnas($posts));
        $this->_view->assign('titulo', 'Post');
        $this->_view->assign('tituloView', 'Últimos Posts');
        $this->_view->renderizar('index', 'post');
    }

    public function nuevo()
    {
        Session::acceso('especial');
        $this->_view->assign('titulo', "Nuevo post");
        $this->_view->setJs(array('nuevo'));

        //
        if ($this->getInt('guardar') == 1) {
            $this->_view->assign('datos', $_POST); //TODO limpiar $_POST
            //validar
            if (!$this->getTexto('titulo')) {
                $this->_view->assign('_error', 'Debe introducir el título del post');
                $this->_view->renderizar('nuevo', 'post');
                exit;
            }
            if (!$this->getTexto('cuerpo')) {
                $this->_view->assign('_error', 'Debe introducir el cuerpo del post');
                $this->_view->renderizar('nuevo', 'post');
                exit;
            }

            //guardar datos
            $this->_post->insertarPost(
                    $this->getPostParam('titulo'), $this->getPostParam('cuerpo')
            );
            $this->redireccionar('post');
        }



        $this->_view->renderizar('nuevo', 'post');
    }

    public function editar($id)
    {
        if (!$this->filtrarInt($id)) {//Si id no es entero
            $this->redireccionar('post');
        }

        if (!$this->_post->getPost($this->filtrarInt($id))) {//Si no existe el post
            $this->redireccionar('post');
        }

        $this->_view->assign('titulo', "Editar post");
        $this->_view->setJs(array('nuevo'));


        if ($this->getInt('guardar') == 1) {
            $this->_view->datos = $_POST; //TODO limpiar $_POST
            //validar
            if (!$this->getTexto('titulo')) {
                $this->_view->assign('_error', 'Debe introducir el título del post');
                $this->_view->renderizar('editar', 'post');
                exit;
            }
            if (!$this->getTexto('cuerpo')) {
                $this->_view->assign('_error', 'Debe introducir el cuerpo del post');
                $this->_view->renderizar('editar', 'post');
                exit;
            }

            //guardar datos
            $this->_post->editarPost(
                    $this->filtrarInt($id), $this->getPostParam('titulo'),
                    $this->getPostParam('cuerpo')
            );
            $this->redireccionar('post');
        }


        $this->_view->assign('datos', $this->_post->getPost($this->filtrarInt($id)));
        $this->_view->renderizar('editar', 'post');
    }

    public function eliminar($id)
    {
        if (!$this->filtrarInt($id)) {//Si id no es entero
            $this->redireccionar('post');
        }

        if (!$this->_post->getPost($this->filtrarInt($id))) {//Si no existe el post
            $this->redireccionar('post');
        }

        $this->_post->eliminarPost($this->filtrarInt($id));

        $this->redireccionar('post');
    }

    public function crear100()
    {
        $this->_post->crear100();

        $this->redireccionar('post');
    }

    public function borrarPruebas()
    {
        $this->_post->borrarPruebas();

        $this->redireccionar('post');
    }

}
