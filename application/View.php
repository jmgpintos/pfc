<?php

/*
 * A diferencia de los controladores, las vistas son instanciadas desde esta clase principal
 */

require_once ROOT . 'libs' . DS . 'smarty' . DS . 'libs' . DS . 'Smarty.class.php';

class View extends Smarty
{

    private $_controlador;
    private $_js;

    public function __construct(Request $peticion)
    {
        parent::__construct();
        $this->_controlador = $peticion->getControlador();
        $this->_js = array();
    }

    public function renderizar($vista, $item = false)
    {
        //Directorios para smarty
        $this->setTemplateDir(ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS);
        $this->setConfigDir(ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'configs' . DS);
        $this->setCacheDir(ROOT . 'tmp' . DS . 'cache' . DS);
        $this->setCompileDir(ROOT . 'tmp' . DS . 'template' . DS);

        $rutaDefaultLayout = ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS;
        $_params = array(
            'ruta_css' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/css/',
            'ruta_img' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/img/',
            'ruta_js' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/js/',
            'menu' => self::getMenu(),
            'item' => $item,
            'root' => BASE_URL,
            'includes' => array(
                'header' => $rutaDefaultLayout . 'header.tpl',
                'menu' => $rutaDefaultLayout . 'menu.tpl',
                'titulo' => $rutaDefaultLayout . 'titulo.tpl',
                'mensajes' => $rutaDefaultLayout . 'mensajes.tpl',
                'footer' => $rutaDefaultLayout . 'footer.tpl',
                'info_debug' => $rutaDefaultLayout . 'info_debug.tpl',
            ),
            'configs' => array(
                'app_name' => APP_NAME,
                'app_slogan' => APP_SLOGAN,
                'app_company' => APP_COMPANY,
            ),
        );

        if (count($this->_js)) {
            $js = $this->_js;
            $_layoutParams['js'] = $js;
        }

        $rutaView = ROOT . 'views' . DS . $this->_controlador . DS . $vista . '.tpl';

        if (is_readable($rutaView)) {
            $this->assign('_contenido', $rutaView);
        }
        else {
            throw new Exception('error de vista ');
        }

        $this->assign('_layoutParams', $_params);
        $this->display('template.tpl');
    }

    protected function getMenu()
    {
        $menu = array(
            array(
                'id' => 'inicio',
                'titulo' => 'Inicio',
                'enlace' => BASE_URL
            ),
            array(
                'id' => 'post',
                'titulo' => 'Posts',
                'enlace' => BASE_URL . 'post'
            ),
        );

        if (Session::get('autenticado')) {
            if (Session::esAdmin() || Session::esEspecial()) {
                $menu[] = array(
                    'id' => 'usuario',
                    'titulo' => 'Usuarios',
                    'enlace' => BASE_URL . 'usuario'
                );
            }
            $menu[] = array(
                'id' => 'login',
                'titulo' => 'Logout',
                'enlace' => BASE_URL . 'login/cerrar'
            );
        }
        else {
            $menu[] = array(
                'id' => 'login',
                'titulo' => 'Iniciar sesión',
                'enlace' => BASE_URL . 'login'
            );
            $menu[] = array(
                'id' => 'registro',
                'titulo' => 'Registro',
                'enlace' => BASE_URL . 'registro'
            );
        }


        return $menu;
    }

    public function setJs(array $js)
    {
        if (is_array($js) && count($js)) {
            for ($i = 0; $i < count($js); $i++) {
                $this->_js[] = BASE_URL . 'views/' . $this->_controlador . '/js/' . $js[$i] . '.js';
            }
        }
        else {
            throw new Exception('Error de js');
        }
    }

}
