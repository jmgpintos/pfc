<?php

class registroModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function verificarUsuario($usuario)
    {

        $id = $this->_db->query(
                "SELECT id FROM usuario WHERE username = '$usuario'"
        );
        if ($id->fetch()) {
            return true;
        }

        return false;
    }

    public function verificarEmail($email)
    {

        $id = $this->_db->query(
                "SELECT id FROM usuario WHERE email = '$email'"
        );
        if ($id->fetch()) {
            return true;
        }

        return false;
    }
    
    //TODO mandar parametros como array
    public function registrarUsuario($nombre, $username, $password, $email)
    {
        /*id            
          id_rol                
          estado                
          username              
          password              
          nombre                
          apellidos             
          email                 
          telefono              
          ultimo_acceso         
          id_usuario_creacion   
          fecha_creacion        
          id_usuario_modificacion
          fecha_modificacion 
         */
        $role_usuario = 3;
        $estado_nuevo = 3;
        $this->_db->prepare(
                "INSERT INTO usuario (id, id_rol, estado, username, password, nombre,email) VALUES "
                . " (null, :role, :estado, :username, :password, :nombre, :email  )"
                )
                ->execute(array(
                    ':nombre'   => $nombre,
                    ':role'     => $role_usuario,
                    ':estado'   => $estado_nuevo,
                    ':username' => $username,
                    ':password' => Hash::getHash('sha1', $password, HASH_KEY),
                    ':email'    => $email,
                ));
    }

}
