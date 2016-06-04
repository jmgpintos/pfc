<?php

class usuarioController extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->_model = $this->loadModel('usuario');
        $this->_tabla = 'usuario';
        $this->_modulo = 'usuario';
    }

    public function index($pagina = false)
    {
        $this->_log->write(__METHOD__ . ' pagina=' . $pagina, LOG_DEBUG);

        $pagina = $this->getNumPagina($pagina);
        $this->_view->setJs(array('confirmarBorrar', 'validacion'));

        $this->asignarMensajes();

        $this->getLibrary('paginador');
        $paginador = new Paginador();
        $data = $paginador->paginar($this->_model->getAllUsuarios(), $pagina);

//        vardumpy($data);
        $this->_view->assign('data', $data);
        $this->ponerPaginacion($paginador, $this->_tabla);
        $this->_view->assign('columnas', $this->_model->getColumnas($data));
        $this->_view->assign('cuenta', $this->_model->getCount($this->_tabla));
        $this->_view->assign('titulo', 'Usuarios');
        $this->_view->assign('tituloView', 'Lista de usuarios');
        $this->_view->assign('controlador', $this->_modulo. '/');
        
        $this->_view->renderizar('index', $this->_modulo);
    }

    public function editar($id)
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);

        $this->asignarMensajes();

        $this->_view->assign('titulo', 'Usuarios');
        $this->_view->assign('tituloView', 'Editar usuario');
        $this->_view->setJs(array('validacion'));

        if ($this->getInt('guardar') == 1) {
            $this->_view->assign('datos', $_POST); //TODO limpiar $_POST

            $this->validar(ACCION_EDITAR);
//guardar datos
            $campos = array(
                ':username' => $this->getAlphaNum('username'),
                ':nombre' => $this->getPostParam('nombre'),
                ':apellidos' => $this->getPostParam('apellidos'),
                ':email' => $this->getPostParam('email'),
                ':telefono' => $this->getPostParam('telefono')
            );
            $this->_model->editarRegistro($this->_tabla, $this->filtrarInt($id), $campos);

            Session::setMensaje('El Usuario ha sido editado correctamente');

            $this->_log->write('EDITAR USUARIO id: ' . $id);
            $this->redireccionar($this->_modulo);
        }

        $data = $this->_model->getById($this->_tabla, $this->filtrarInt($id));
        $this->_view->assign('data', $data);

        $this->_view->renderizar('editar', $this->_modulo);
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

            $this->validar(ACCION_NUEVO);

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
            $campos = array();
            $this->_model->insertarRegistro(
                    $this->_tabla,
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

    public function eliminar($id)
    {
        $id = $this->filtrarInt($id);
        $this->_log->write(__METHOD__, LOG_DEBUG);

        if (!($data = $this->_model->getById($this->_tabla, $id))) {
            Session::setError('El Usuario con id #' . $id . ' no existe');
            $this->redireccionar($this->_modulo);
        }

        if ($this->_model->eliminarRegistro($this->_tabla, $id)) {
            Session::setMensaje(
                    "El Usuario # " . $data['id'] . " - <strong>" . $data['username'] . "</strong>"
                    . " correspondiente a <i>" . $data['nombre'] . " " . $data['apellidos']. "</i>"
                    . " ha sido borrado correctamente");

            $this->_log->write('BORRAR USUARIO -' . $data['id'] . " - " . $data['username'],
                    LOG_NOTICE);
        }
        else {
            Session::setError('Se ha producido un error al borrar el registro');
            $this->_log->write('ERROR AL BORRAR USUARIO -' . $data['id'] . " - " . $data['username']);
        }

        $this->redireccionar($this->_modulo . '/index/' . Session::get('pagina'));
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

    private function validar($accion)
    {
        $this->_log->write(__METHOD__, LOG_DEBUG);


        $error = false;
        $mensaje = null;

        if (!$this->getAlphaNum('username')) {
            $mensaje = 'Debe introducir un nombre de usuario';
            $error = true;
        }
        elseif (!$this->getPostParam('nombre')) {
            $mensaje = 'Debe introducir el nombre del usuario';
            $error = true;
        }
        elseif (!$this->getPostParam('apellidos')) {
            $mensaje = 'Debe introducir los apellidos del usuario';
            $error = true;
        }
        elseif (!$this->validarEmail($this->getPostParam('email'))) {
            put($this->getPostParam('email'));
            $mensaje = 'Debe introducir una dirección de correo electrónico válida';
            $error = true;
        }

        $row = $this->_model->getById($this->_tabla, $this->getInt('id'));
        if ($accion == ACCION_NUEVO) {//Comprobar que el usuario existe
            $row = $this->_model->getUsuario(
                    $this->getAlphaNum('usuario'), $this->getSql('pass')
            );

            if (!$error) {
                if (!$row) {
                    $mensaje = 'Usuario y/o password incorrecto';
                    $error = true;
                }
                else if ($row['estado'] != 1) {
                    $mensaje = 'Este usuario no está habilitado';
                    $error = true;
                }
            }
        }

        if ($error) {
            $this->_view->assign('_error', $mensaje);
            $this->_view->assign('data', $_POST);
            $this->_view->renderizar($accion, 'login');
            exit;
        }
        if ($accion == ACCION_NUEVO) {
            Session::setMensaje('Usuario editado <strong>' . $row['username'] . '</strong>');
        }
        else {
            Session::setMensaje('Usuario creado como <strong>' . $row['username'] . '</strong>');
        }
        return $row;
    }

}
