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

    /**
     * Constructor por defecto
     * instancia un objeto para acceder a la base de datos
     * instancia un objeto para poder escribir en el archivo log
     */
    public function __construct()
    {
        $this->_db = new Database();
        $this->_log = new Log();
    }

    /**
     * Devuelve el id del último registro insertado
     * @return int
     */
    public function getLast_id()
    {
        return $this->_lastID;
    }

    /**
     * Devuelve el total de registros de la tabla
     * @param String $table Nombre de la tabla con o sin prefijo (TABLES_PREFIX)
     * @return int
     */
    public function getCount($table)
    {
        $this->_log->write(__METHOD__ . ' - table =>' . $table);
        $table = $this->getTableName($table);

        $SQL = "SELECT count(*) FROM $table ";

        $row = $this->_db->query($SQL);

        return $row->fetch()[0];
    }

    /**
     * Devuelve un array con los resultados de una consulta SQL
     * @param String $sql Consulta SQL
     * @return array
     */
    public function getSQL($sql)
    {
        $this->_log->write(__METHOD__ . ' - sql =>' . $sql);

        $row = $this->_db->query($sql);

        $rs = $row->fetchAll(PDO::FETCH_ASSOC);

        return $rs;
    }

    /**
     * Lee todos los registros 
     * 
     * @param string $table Nombre de la tabla con o sin prefijo (TABLES_PREFIX)
     * @return array
     */
    public function getAll($table, array $campos = array())
    {
        $this->_log->write(__METHOD__
                . ' - tabla =>' . $table
                . ', campos: ' . array_to_str($campos)
                );

        $table = $this->getTableName($table);

        if (count($campos)) {
            $listaCampos = 'id, ';

            $listaCampos .= implode(", ", $campos);
        }
        else {
            $listaCampos = '*';
        }
        $SQL = "SELECT " . $listaCampos . " FROM $table ORDER BY id";
        $row = $this->_db->query($SQL);

        return $row->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lee un registro
     * 
     * @param String $table Nombre de la tabla con o sin prefijo (TABLES_PREFIX)
     * @param int $index valor del campo 'id' del registro buscado
     * @return array 
     */
    public function getById($table, $index)
    {
        $this->_log->write(__METHOD__
                . ' - tabla => ' . $table
                . ' index => ' . $index
                );

        $table = $this->getTableName($table);

        $id = (int) $index;
        $sql = "SELECT * FROM $table WHERE id=$id";

        $row = $this->_db->query($sql);

        return $row->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Inserta un registro
     * $table es una tabla cuyo primer campo es un id autonumerico
     * modifica el valor de $this->_lastID 
     * 
     * @param string $table Nombre de la tabla con o sin prefijo (TABLES_PREFIX)
     * @param array $campos lista de campos y valores del tipo (':nombre_campo' => 'valor_campo')
     * @return int valor del campo 'id' del registro insertado
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
                . ' registro insertado - tabla =>' . $table
                . ', campos: ' . array_to_str($campos));

        return $this->_lastID;
    }

    /**
     * Actualiza un registro
     * 
     * @param string $table Nombre de la tabla con o sin prefijo (TABLES_PREFIX)
     * @param int $index valor del campo 'id' del registro a editar
     * @param array $campos  lista de campos y valores del tipo (':nombre_campo' => 'valor_campo')
     * @return array
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
                . ' registro editado - tabla =>' . $table
                . ', index => ' . $index
                . ', campos: ' . array_to_str($campos));
        
        $stmt = $this->_db->prepare($sql);
        return $stmt->execute($campos);
    }

    /**
     * Elimina un registro
     * 
     * @param string $table Nombre de la tabla con o sin prefijo (TABLES_PREFIX)
     * @param int $index valor del campo 'id' del registro a borrar
     * 
     * return boolean|array si error -> false|array con los valores del registro borrdo
     */
    public function eliminarRegistro($table, $index)
    {
        $this->_log->write(__METHOD__
                . ' - tabla =>' . $table
                . ', index => ' . $index
                );
        
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
     * @param string $table Nombre de la tabla con o sin prefijo (TABLES_PREFIX)
     * @param array $campos lista de campos y valores del tipo ('nombre_campo' => 'valor_campo')
     * @return boolean 
     */
    public function existeRegistro($table, array $campos)
    {
        $this->_log->write(__METHOD__
                . ' - tabla =>' . $table
                . ', campos: ' . array_to_str($campos)
                );

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
     * Cambia la fecha del último acceso del usuario con id $index
     * @param type $index id del usuario
     */
    public function cambiarUltimoAcceso($index)
    {
        $this->_log->write(__METHOD__
                . ' - index => ' . $index
                );
        
        $id = (int) $index;

        $table = TABLES_PREFIX . 'usuario';

        $campos[':id'] = $id;

        $sql = "UPDATE $table SET ultimo_acceso=now() WHERE id = :id";

        $this->_db->prepare($sql)
                ->execute($campos);
    }

    /**
     * Devuelve los índices del array (nombres de los campos) excepto el primero (id)
     * útil para cabeceras de tabla
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
     * @param string $table Nombre de la tabla con o sin prefijo (TABLES_PREFIX)
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

    /**
     * Devuelve los campos id, nombre de una tabla (útil para poblar combos)
     * 
     * @param string $table Nombre de la tabla con o sin prefijo (TABLES_PREFIX)
     * @param string $order cadena de ordenación SQL
     * @return array
     */
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

    /**INFO SOBRE TABLAS**/
    
    /**
     * Devuelve un array con los nombres de los campos 
     * 
     * @param string $table Nombre de la tabla con o sin prefijo (TABLES_PREFIX)
     * @return array 
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

    
    public function primero($tabla)
    {
        $sql ="SELECT id FROM $tabla ORDER BY id LIMIT 1" ;
        
        $row = $this->_db->query($sql);
        
        return $row->fetchAll(PDO::FETCH_ASSOC)[0]['id'];
        
    }
    
    public function ultimo($tabla)
    {
        $sql ="SELECT id FROM $tabla ORDER BY id DESC LIMIT 1" ;
        
        $row = $this->_db->query($sql);

        return $row->fetchAll(PDO::FETCH_ASSOC)[0]['id'];
        
    }
     /************** TEMPORAL (para pruebas)*******************/
    
    
    /**
     * 
     * Borrar los registros a partir de un id dado
     * 
     * @param string $table Nombre de la tabla con o sin prefijo (TABLES_PREFIX)
     * @param type $id_minimo valor del campo id a partir del cual se borran los registros
     */
    public function borrarPruebas($tabla, $id_minimo)
    {
        $post = $this->_db->query("DELETE FROM $tabla WHERE id > $id_minimo ");
    }

}
