<?php

/*
 * Clase Model, de la que heredan todos los modelos
 * 
 * Métodos para acceder a los datos
 */

class Model {

    /**
     * Objeto database sobre el que se ejecutan las consultas
     * @var Database 
     */
    protected $_db;

    /**
     * Id del último registro insertado.
     * @var int
     */
    protected $_lastID;

    public function __construct() {
        $this->_db = new Database();
    }

    public function getLast_id() {
        return $this->_lastID;
    }

    public function getSQL($sql) {
        $row = $this->_db_->query($sql);

        $rs = $row->fetchAll(PDO::FETCH_ASSOC);

        return $rs;
    }

    /**
     * Recuperar todos los registros de la tabla $table
     * 
     * @param string $table 
     * @return array RecordSet con todos los registros de $table
     */
    public function getAll($table) {

        $table = $this->getTableName($table);

        $SQL = "SELECT * FROM $table ";

        $row = $this->_db_->query($SQL);

        return $row->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recuperar el registro de la tabla $table con id = $id
     * 
     * @param type $table
     * @param type $index
     * @return array RecorSet con el registro solicitado
     */
    public function getById($table, $index) {

        $table = $this->getTableName($table);

        $id = (int) $index;
        $sql = "SELECT * FROM $table WHERE id=$id";

//        put($sql);
        $row = $this->_db_->query($sql);

        return $row->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Inserta un registro en la tabla $table con los valores de $campos
     * $table es una tabla cuyo primer campo es un id autonumerico
     * 
     * @param string $table tabla en la que se inserta el registro
     * @param array $campos lista de campos y valores del tipo (':nombre_campo' => 'valor_campo')
     * @return int id del registro insertado
     */
    public function insertarRegistro($table, array $campos) {

        $table = $this->getTableName($table);

        if (in_array('creador', $this->getFields($table))) {
            $campos[':creador'] = Session::getId();
        }

        //construir SQL
        $str_campos = $str_columnas = '';

        foreach (array_keys($campos) as $k) {
            $key = ltrim($k, ":");
            $str_campos .= ':' . $key . ', ';
            $str_columnas.=ltrim($k, ":") . ', ';
        }

        $sql = "INSERT INTO $table (" . rtrim($str_columnas, ', ') . ") VALUES(" . rtrim($str_campos, ', ') . ")"; //el primer campo (null) es el id
        //        vardump($campos);
        //        put($sql);exit;
        //ejecutar consulta
        $this->_db_->prepare($sql)
                ->execute($campos);

        $this->_lastID = $this->_db_->lastInsertId(); //guardar ID del registro insertado
//        put($table);
//        put(array_to_str($campos));
//        vardumpy($campos);

        if ($table != $this->tbl_log && $table != $this->tbl_borrado) {

            Log::info('registro insertado', array('tabla' => $table, 'campos' => array_to_str($campos)));
        }
//        puty($this->_lastID);
        return $this->_lastId;
    }

    /**
     * Actualiza el registro con id = $id en la tabla $table con los valores de $campos
     * 
     * @param string $table tabla en la que se inserta el registro
     * @param int $index PK del registro a editar
     * @param array $campos  lista de campos y valores del tipo (':nombre_campo' => 'valor_campo')
     * @return //TODO
     */
    public function editarRegistro($table, $index, array $campos) {
        $table = $this->getTableName($table);

        $id = (int) $index;

        //Agregar modificador y fecha_modificacion si la tabla tiene esos campos.
        if (in_array('modificador', $this->getFields($table))) {
            $campos[':modificador'] = Session::getId();
        }
        if (in_array('fecha_modificacion', $this->getFields($table))) {
            $campos[':fecha_modificacion'] = FechaHora::Hoy();
        }

        //construir SQL
        $tmp_campos = '';
        foreach (array_keys($campos) as $k) {
            $key = ltrim($k, ':');
            $tmp_campos .= $key . '= :' . $key . ', ';
        }

        $srt_campos = rtrim($tmp_campos, ', ');

        $campos[':id'] = $id; //Añadir campo id a lista campos para execute

        $sql = "UPDATE $table SET " . $srt_campos . " WHERE id = :id";

        //put($sql);
        //var_dump($campos);exit;
        Log::info('registro editado', array('tabla' => $table, 'campos' => array_to_str($campos)));

        $stmt = $this->_db_->prepare($sql);
        return $stmt->execute($campos);
    }

    /**
     * Elimina el registro con PK $id de la tabla $table
     * 
     * @param string $table Tabla de la que se borra el registro
     * @param int $index PK del registro a borrar
     * 
     * return bool
     */
    public function eliminarRegistro($table, $index) {
        $table = $this->getTableName($table);

        $id = (int) $index;

        //Datos de registro borrado
        $reg_borrado = $this->getById($table, $id);

        $string_registro_borrado = array_to_str($reg_borrado);

        $tbl_borrado = $this->getTableName('borrado');
        $campos = array(
            ':user' => Session::get('id_usuario'),
            ':tabla' => $table,
            ':descripcion' => $string_registro_borrado
        );

        //Borrar registro
        $sql = "DELETE FROM $table WHERE id=$id";

//        put(__METHOD__);puty($sql);

        if ($this->_db_->query($sql)) {
            //Guardar registro borrado
            $this->insertarRegistro($tbl_borrado, $campos);
            Log::warning(
                    'registro borrado', array(
                'tabla' => $table,
                'campos' => $string_registro_borrado,
            ));
            return true;
        } else {
            Log::notice('intento de borrado de registro', array(
                'tabla' => $table,
                'campos' => $string_registro_borrado,
            ));
            return false;
        }
    }

    /**
     * Cambia la fecha delúltimo acceso del usuario con id $index
     * @param type $indexid del usuario
     */
    public static function cambiarUltimoAcceso($index) {
        $id = (int) $index;

        $table = TABLES_PREFIX . 'usuarios';

        $campos[':id'] = $id;

        $sql = "UPDATE $table SET ultimo_acceso=now() WHERE id = :id";

        $this->_db->prepare($sql)
                ->execute($campos);
    }

    /**
     * Devuelve un array con los nombres de los capos de la tabla $table
     * @param string $table Nombre de la tabla
     * @return array Nombre de los campos de $table
     */
    public function getFields($table) {

        $table = $this->getTableName($table);

        $rs = $this->_db->query("SELECT * FROM $table LIMIT 0");
        for ($i = 0; $i < $rs->columnCount(); $i++) {
            $col = $rs->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        return $columns;
    }

    /**
     * Devuelve un array con info (DESCRIBE) sobre la tabla $table
     * @param string $table Nombre de la tabla
     * @return array Info de $table
     */
    public function getTableInfo($table) {
        $sql = "SHOW COLUMNS FROM  {$table}";

        $row = $this->_db->query($sql);

        return $row->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getColumnas($data) {
        put(__METHOD__);
        if (count($data)) {
            return array_keys($data[0]);
        } else {
            return array();
        }
    }

    /**
     * Agrega el prefijo al nombre de la tabla
     * 
     * @param string $table
     * @return string
     */
    public function getTableName($table) {

        $len_prefix = strlen(TABLE_PREFIX);

        if (substr($table, 0, $len_prefix) == TABLE_PREFIX) {
            return $table;
        } else {
            return TABLES_PREFIX . $table;
        }
    }

}
