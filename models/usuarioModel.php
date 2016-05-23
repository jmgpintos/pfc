<?php

class usuarioModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllUsuarios()
    {
        //select usuario.id, rol.nombre, estado, username, usuario.nombre, apellidos, email, ultimo_acceso from usuario, rol where id_rol=rol.id;

        $table = 'usuario';

        $table = $this->getTableName($table);

        $SQL = "SELECT usuario.id, rol.nombre, estado, username,"
                . " CONCAT(apellidos,', ', usuario.nombre) as nombre, email, ultimo_acceso "
                . "FROM usuario, rol "
                . "WHERE id_rol=rol.id ";

        $row = $this->_db->query($SQL);

        return $row->fetchAll(PDO::FETCH_ASSOC);
    }

    public function nuevoUsuarioAuto()
    {
        $nombres = array('María', 'Laura', 'Cristina', 'Marta', 'Sara', 'Andrea',
            'Ana', 'Alba', 'Paula', 'Sandra', 'David', 'Alejandro', 'Daniel',
            'Javier', 'Sergio', 'Adrián', 'Carlos', 'Pablo', 'Álvaro',);
        $apellidos = array(
            'Alonso', 'Álvarez', 'Blanco', 'Calvo', 'Cano',
            'Castillo', 'Castro', 'Delgado', 'Díaz', 'Díez',
            'Domínguez', 'Fernández', 'García', 'Garrido', 'Gil',
            'Gómez', 'González', 'Gutiérrez', 'Hernández', 'Iglesias',
            'Jiménez', 'López', 'Lozano', 'Marín', 'Martín',
            'Martínez', 'Medina', 'Molina', 'Morales', 'Moreno',
            'Muñoz', 'Navarro', 'Núñez', 'Ortega', 'Ortiz',
            'Pérez', 'Prieto', 'Ramírez', 'Ramos', 'Rodríguez',
            'Romero', 'Rubio', 'Ruiz', 'Sánchez', 'Santos',
            'Sanz', 'Serrano', 'Suárez', 'Torres', 'Vázquez'
        );
        $nombre = $nombres[rand(0, count($nombres))];
        $apellido = $apellidos[rand(0, count($apellidos))];

        $rand = rand(0, 99999999);
        $id_rol = rand(1, 3);
        $mail = limpiar(strtolower($nombre)) . $rand . "@" . limpiar(strtolower($apellido)) . '.com';
        $tel = rand(600000000, 799999999);
        
        $codigo = rand(11111111, 99999999);
        $SQL = "INSERT INTO usuario(id_rol, nombre, apellidos, username, password, email, telefono, estado, fecha_creacion,codigo)"
                . "VALUES($id_rol, '$nombre', '$apellido','user" . $rand . "','" . Hash::getHash('sha1',
                        '1234', HASH_KEY) . "',' $mail ','" . $tel . "',1,now(),$codigo)";
        $this->_db->query($SQL);
    }
    

}
