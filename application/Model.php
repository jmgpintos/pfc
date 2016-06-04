<?php

/*
 * Clase Model, de la que heredan todos los modelos
 * 
 * Métodos para acceder a los datos
 */

class Model
{

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
    protected $_log;

    public function __construct()
    {
        $this->_db = new Database();
        $this->_log = new Log();
    }

    public function getLast_id()
    {
        return $this->_lastID;
    }

    public function getCount($table)
    {
        $table = $this->getTableName($table);

        $SQL = "SELECT count(*) FROM $table ";

        $row = $this->_db->query($SQL);

        return $row->fetch()[0];
    }

    public function getSQL($sql)
    {
        $this->_log->write(__METHOD__ . ' - sql =>' . $sql);

        $row = $this->_db->query($sql);

        $rs = $row->fetchAll(PDO::FETCH_ASSOC);

        return $rs;
    }

    /**
     * Recuperar todos los registros de la tabla $table
     * 
     * @param string $table 
     * @return array RecordSet con todos los registros de $table
     */
    public function getAll($table, array $campos = array())
    {
        $this->_log->write(__METHOD__
                . ' - tabla =>' . $table
                . ', campos: ' . array_to_str($campos));

        $table = $this->getTableName($table);

        if (count($campos)) {
            $listaCampos = 'id, ';

            $listaCampos .= implode(", ", $campos);
        }
        else {
            $listaCampos = '*';
        }
        $SQL = "SELECT " . $listaCampos . " FROM $table ";
        $row = $this->_db->query($SQL);

        return $row->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recuperar el registro de la tabla $table con id = $id
     * 
     * @param type $table
     * @param type $index
     * @return array RecorSet con el registro solicitado
     */
    public function getById($table, $index)
    {

        $table = $this->getTableName($table);

        $id = (int) $index;
        $sql = "SELECT * FROM $table WHERE id=$id";

        $row = $this->_db->query($sql);

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
    public function insertarRegistro($table, array $campos)
    {
        $table = $this->getTableName($table);

        if (in_array('id_usuario_creacion', $this->getFields($table))) {
            $campos[':id_usuario_creacion'] = Session::getId();
        }
        if (in_array('fecha_creacion', $this->getFields($table))) {
            $campos[':fecha_creacion'] = FechaHora::Hoy();
        }

        //construir SQL
        $str_campos = $str_columnas = '';

        foreach (array_keys($campos) as $k) {
            $key = ltrim($k, ":");
            $str_campos .= ':' . $key . ', ';
            $str_columnas.=ltrim($k, ":") . ', ';
        }

        $sql = "INSERT INTO $table (" . rtrim($str_columnas, ', ') . ") VALUES(" . rtrim($str_campos,
                        ', ') . ")"; //el primer campo (null) es el id
        // 
        //ejecutar consulta
        $this->_db->prepare($sql)
                ->execute($campos);

        $this->_lastID = $this->_db->lastInsertId(); //guardar ID del registro insertado

        $this->_log->write(__METHOD__
                . 'registro insertado - tabla =>' . $table
                . ', campos: ' . array_to_str($campos));

        return $this->_lastID;
    }

    /**
     * Actualiza el registro con id = $id en la tabla $table con los valores de $campos
     * 
     * @param string $table tabla en la que se inserta el registro
     * @param int $index PK del registro a editar
     * @param array $campos  lista de campos y valores del tipo (':nombre_campo' => 'valor_campo')
     * @return //TODO
     */
    public function editarRegistro($table, $index, array $campos)
    {
        $table = $this->getTableName($table);

        $id = (int) $index;

        //Agregar modificador y fecha_modificacion si la tabla tiene esos campos.
        if (in_array('id_usuario_modificacion', $this->getFields($table))) {
            $campos[':id_usuario_modificacion'] = Session::getId();
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

        $this->_log->write(__METHOD__
                . 'registro editado - tabla =>' . $table
                . ', campos: ' . array_to_str($campos));
        $stmt = $this->_db->prepare($sql);
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
    public function eliminarRegistro($table, $index)
    {
        $table = $this->getTableName($table);

        $id = (int) $index;

        //Datos de registro borrado
        $reg_borrado = $this->getById($table, $id);

        //Borrar registro
        $sql = "DELETE FROM $table WHERE id=$id";

        if ($this->_db->query($sql)) {
            return $reg_borrado;
        }
        else {
            return false;
        }
    }

    /**
     * Verifica si existe un registro con los datos dados
     * 
     * @param string $table Tabla en la que busca
     * @param array $campos lista de campos y valores del tipo ('nombre_campo' => 'valor_campo')
     * @return boolean
     */
    public function existeRegistro($table, array $campos)
    {

        $table = $this->getTableName($table);

        $condicion = '';

        foreach ($campos as $k => $v) {
            $condicion .= ltrim($k, ':') . '="' . $v . '" AND ';
        }

        $sql = "SELECT * FROM $table WHERE " . rtrim($condicion, ' AND ') . " LIMIT 1";

        $row = $this->_db_->query($sql);

        if ($row->fetch()) {
            return true;
        }

        return false;
    }

    /**
     * Cambia la fecha delúltimo acceso del usuario con id $index
     * @param type $indexid del usuario
     */
    public function cambiarUltimoAcceso($index)
    {
        $id = (int) $index;

        $table = TABLES_PREFIX . 'usuario';

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
    public function getFields($table)
    {

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
    public function getTableInfo($table)
    {
        $sql = "SHOW COLUMNS FROM  {$table}";

        $row = $this->_db->query($sql);

        return $row->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve los índices del array (nombres de los campos) excepto el primero (id)
     * @param type $data
     * @return $array
     */
    public function getColumnas($data)
    {
        if (count($data)) {
            $keys = array_keys($data[0]);
            array_shift($keys);
            return $keys;
        }
        else {
            return array();
        }
    }

    /**
     * Agrega el prefijo al nombre de la tabla
     * 
     * @param string $table
     * @return string
     */
    public function getTableName($table)
    {

        $len_prefix = strlen(TABLES_PREFIX);

        if (substr($table, 0, $len_prefix) == TABLES_PREFIX) {
            return $table;
        }
        else {
            return TABLES_PREFIX . $table;
        }
    }

    public function getTabla($table, $order = false)
    {
        if (!$order) {
            $order = 'nombre';
        }

        $table = $this->getTableName($table);

        $SQL = "SELECT id, nombre FROM {$table} ORDER BY {$order}";

        $row = $this->_db->query($SQL);

        return $row->fetchAll(PDO::FETCH_ASSOC);
    }

    public function borrarPruebas($tabla, $id_minimo)
    {
        $post = $this->_db->query("DELETE FROM $tabla WHERE id > $id_minimo ");
    }

}
