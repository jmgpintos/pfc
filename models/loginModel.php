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
                . " AND password = '" . Hash::getHash('sha1', $password, HASH_KEY) . "'"
        );

        return $datos->fetch(PDO::FETCH_ASSOC);
    }

}
