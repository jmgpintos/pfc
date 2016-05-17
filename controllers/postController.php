<?php

class postController extends Controller
{

    private $_post;

    function __construct()
    {
        parent::__construct();
        $this->_post = $this->loadModel('post');
    }

    public function index()
    {

        $this->_view->posts = $this->_post->getPosts();
        $this->_view->columnas = $this->_post->getColumnas($this->_view->posts);
        $this->_view->titulo = "Post";
        $this->_view->renderizar('index', 'post');
    }

    public function nuevo()
    {
        $this->_view->titulo = "Nuevo post";
        $this->_view->setJs(array('nuevo'));

        //
        if ($this->getInt('guardar') == 1) {
            $this->_view->datos = $_POST; //TODO limpiar $_POST
            //validar
            if (!$this->getTexto('titulo')) {
                $this->_view->_error = 'Debe introducir el título del post';
                $this->_view->renderizar('nuevo', 'post');
                exit;
            }
            if (!$this->getTexto('cuerpo')) {
                $this->_view->_error = 'Debe introducir el cuerpo del post';
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

        $this->_view->titulo = "Editar post";
        $this->_view->setJs(array('nuevo'));


        if ($this->getInt('guardar') == 1) {
            $this->_view->datos = $_POST; //TODO limpiar $_POST
            //validar
            if (!$this->getTexto('titulo')) {
                $this->_view->_error = 'Debe introducir el título del post';
                $this->_view->renderizar('editar', 'post');
                exit;
            }
            if (!$this->getTexto('cuerpo')) {
                $this->_view->_error = 'Debe introducir el cuerpo del post';
                $this->_view->renderizar('editar', 'post');
                exit;
            }

            //guardar datos
            $this->_post->editarPost(
                    $this->filtrarInt($id), $this->getPostParam('titulo'), $this->getPostParam('cuerpo')
            );
            $this->redireccionar('post');
        }


        $this->_view->datos = $this->_post->getPost($this->filtrarInt($id));
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

}