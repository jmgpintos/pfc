<?php

class usuarioController extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->_model = $this->loadModel('usuario');
        $this->_table = 'usuario';
        $this->_modulo = 'usuario';
    }

    public function index($pagina = false)
    {
        $this->_log->write(__METHOD__ . ' pagina=' . $pagina, LOG_DEBUG);

        if (!$this->filtrarInt($pagina)) {
            $pagina = false;
        }
        else {
            $pagina = (int) $pagina;
        }

        $this->asignarMensajes();

        $this->getLibrary('paginador');
        $paginador = new Paginador();
        $data = $paginador->paginar($this->_model->getAllUsuarios(), $pagina);

//        vardumpy($data);
        $this->_view->assign('data', $data);
        $this->_view->assign('paginacion',
                $paginador->getView('paginacion', $this->_modulo . '/index'));
        $this->_view->assign('columnas', $this->_model->getColumnas($data));
        $this->_view->assign('cuenta', $this->_model->getCount($this->_table));
        $this->_view->assign('titulo', 'Usuarios');
        $this->_view->assign('tituloView', 'Lista de usuarios');
        $this->_view->renderizar('index', $this->_modulo);
    }

    public function nuevo()
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);
        Session::acceso('especial');
        $this->_view->assign('titulo', "Nuevo usuario");
        $this->_view->setJs(array('validacion'));

        //
        if ($this->getInt('guardar') == 1) {
            $this->_view->assign('datos', $_POST); //TODO limpiar $_POST

            $this->validar();

            //guardar datos
            /*
              +-------------------------+------------------+------+-----+---------+----------------+
              | Field                   | Type             | Null | Key | Default | Extra          |
              +-------------------------+------------------+------+-----+---------+----------------+
              | id                      | int(11)          | NO   | PRI | NULL    | auto_increment |
              | id_rol                  | int(11)          | NO   | MUL | NULL    |                |
              | estado                  | tinyint(4)       | NO   |     | NULL    |                |
              | username                | varchar(15)      | NO   |     | NULL    |                |
              | password                | varchar(40)      | NO   |     | NULL    |                |
              | nombre                  | varchar(25)      | YES  |     | NULL    |                |
              | apellidos               | varchar(40)      | YES  |     | NULL    |                |
              | email                   | varchar(254)     | YES  |     | NULL    |                |
              | codigo                  | int(11) unsigned | NO   | UNI | NULL    |                |
              | telefono                | varchar(9)       | YES  |     | NULL    |                |
              | ultimo_acceso           | timestamp        | YES  |     | NULL    |                |
              | id_usuario_creacion     | int(11)          | YES  |     | NULL    |                |
              | fecha_creacion          | timestamp        | YES  |     | NULL    |                |
              | id_usuario_modificacion | int(11)          | YES  |     | NULL    |                |
              | fecha_modificacion      | date             | YES  |     | NULL    |                |
              +-------------------------+------------------+------+-----+---------+----------------+
             */
            $campos = array(
            );
            $this->_model->insertarRegistro(
                    $this->_table,
                    array(
                ':id_rol' => DEFAULT_ROLE,
                ':estado' => 1,
                ':username' => $this->getPostParam('username'),
                ':password' => Hash::getHash('sha1', $this->getPostParam('password'), HASH_KEY),
                ':nombre' => $this->getPostParam('nombre'),
                ':apellidos' => $this->getPostParam('apellidos'),
                ':email' => $this->getPostParam('email'),
                ':telefono' => $this->getPostParam('telefono'),
                ':fecha_creacion' => date('Y-m-d H:i:s'),
                ':ultimo_acceso' => null,
                    )
            );

//            $this->_model->insertarPost(
//                    $this->getPostParam('titulo'), $this->getPostParam('cuerpo')
//            );

            Session::setMensaje('El Usuario ha sido creado correctamente');
            $this->_log->write('NUEVO USUARIO', LOG_NOTICE);
            $this->redireccionar($this->_modulo);
        }

        $this->_view->renderizar('nuevo', $this->_modulo);
    }

    public function nuevoUsuarioAuto()
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);

        for ($index = 0; $index < 20; $index++) {
            $this->_model->nuevoUsuarioAuto();
        }

        $this->redireccionar($this->_modulo);
    }

    public function borrarPruebas()
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);

        $this->_model->borrarPruebas($this->_tabla, 40);

        $this->redireccionar($this->_modulo);
    }

}
