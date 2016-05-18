<?php

class loginModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsuario($usuario, $password)
    {
        $datos = $this->_db->query(
                "SELECT u.*, r.nombre rol FROM usuario u , rol r  WHERE r.id=u.id_rol  "
                . " AND username = '$usuario' "
                . " AND password = '" . md5($password) . "'"
        );
        
        return $datos->fetch();
    }

}
