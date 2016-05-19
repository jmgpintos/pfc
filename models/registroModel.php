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
                "SELECT id, codigo FROM usuario WHERE username = '$usuario'"
        );
        
        return $id->fetch();
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
          codigo
          telefono              
          ultimo_acceso         
          id_usuario_creacion   
          fecha_creacion        
          id_usuario_modificacion
          fecha_modificacion 
         */
        $role_usuario = 3;
        $estado_nuevo = 0;
        $random = rand(11111111, 99999999);
        $this->_db->prepare(
                "INSERT INTO usuario (id, id_rol, estado, username, password, nombre,email,codigo,fecha_creacion) VALUES "
                . " (null, :role, :estado, :username, :password, :nombre, :email, :codigo, now()  )"
                )
                ->execute(array(
                    ':nombre'   => $nombre,
                    ':role'     => $role_usuario,
                    ':estado'   => $estado_nuevo,
                    ':username' => $username,
                    ':password' => Hash::getHash('sha1', $password, HASH_KEY),
                    ':email'    => $email,
                    ':codigo'   => $random,
                ));
    }
    
    public function getUsuario($id, $codigo)
    {
        //TODO validar parametros
        $usuario = $this->_db->query(
                "SELECT * FROM usuario WHERE id = $id AND codigo = '$codigo'"
                );
        
        return $usuario->fetch();
    }
    
    public function activarUsuario($id, $codigo)
    {
        //TODO pasar estado a CONSTANTE
        $this->_db->query(
                "UPDATE usuario SET estado = 1 "
                . "WHERE id = $id AND codigo = '$codigo'"
                );
    }

}
