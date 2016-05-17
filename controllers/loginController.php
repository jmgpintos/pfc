<?php

class loginController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        Session::set('autenticado', true);
        Session::set('level', 'especial');

        Session::set('var1', 'var1');
        Session::set('var2', 'var2');
//        puty(__METHOD__);
        
        $this->redireccionar('login/mostrar');
        
    }
    
    public function mostrar()
    {        
        vardump(info_sesion());
    }
    
    public function cerrar()
    {
        Session::destroy();
        $this->redireccionar('login/mostrar');
    }

}
