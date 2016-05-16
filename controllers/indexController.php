<?php

class indexController extends Controller
{
//    private $_model;

    public function __construct()
    {
        parent::__construct();
//        $this->_model = new Model();
    }

    public function index()
    {
        $post = $this->loadModel('post');
        $posts = $post->getPosts();

        $this->view->columnas = $post->getColumnas($posts);
        $this->_view->posts = $posts;
        $this->_view->titulo = "Portada";
        $this->_view->renderizar('index', 'inicio');
    }

}
