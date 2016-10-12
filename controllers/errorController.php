<?php

//TODO explicar (07 sesiones y control de acceso 10min)
class errorController extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->_view->assign('titulo', 'Error');
        $this->_view->assign('mensaje', $this->_getError());
        $this->_view->renderizar('index');
    }

    
    public function access($codigo)
    {
        $this->_view->assign('titulo',  'Error');
        $this->_view->assign('mensaje', $this->_getError($codigo));
        $this->_view->renderizar('access');
        
    }
    private function _getError($codigo = false)
    {
        if ($codigo) {
            $codigo = $this->filtrarInt($codigo);
            if(is_int($codigo)){
                $codigo = $codigo;
            }
        }
        else {
            $codigo = 'default';
        }

        $error['default'] = 'Ha ocurrido un error y la página no puede mostrarse';
        $error['5050'] = 'Acceso restringido<hr>No tiene permiso para acceder a esta página';
        $error['8080'] = 'Tiempo de sesión excedido';

        if (array_key_exists($codigo, $error)) {
            return $error[$codigo];
        }
        else {
            return $error['default'];
        }
    }

}
