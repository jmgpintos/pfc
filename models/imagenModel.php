<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of imagenModel
 *
 * @author tatooine
 */
class imagenModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Recuperar todos los registros de la tabla $table
     * 
     * @param string $table 
     * @return array RecordSet con todos los registros de $table
     */
     public function getAll($table='imagen', array $campos = array())
    {
        $SQL = "SELECT i.*, c.nombre categoria, l.nombre licencia "
                . "FROM imagen i, licencia l, categoria c "
                . "WHERE l.id=i.id_licencia AND c.id=i.id_categoria; ";
        $row = $this->_db->query($SQL);
        
        return $row->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertarImagen($nombre, $imagen)
    {
        /*
         * ["file_src_name"]=>
          string(15) "portrait-f1.jpg"
          ["file_src_name_body"]=>
          string(11) "portrait-f1"
          ["file_src_name_ext"]=>
          string(3) "jpg"
          ["file_src_mime"]=>
          string(10) "image/jpeg"
          ["file_src_size"]=>
          int(173543)
          ["file_src_error"]=>
          string(1) "0"
          ["file_src_pathname"]=>
          string(14) "/tmp/phpnhUsEy"
          ["file_src_temp"]=>
          string(0) ""
          ["file_dst_path"]=>
          string(34) "/var/www/html/bi/public/img/fotos/"
          ["file_dst_name"]=>
          string(21) "upl_574744be9bcc2.jpg"
          ["file_dst_name_body"]=>
          string(17) "upl_574744be9bcc2"
          ["file_dst_name_ext"]=>
          string(3) "jpg"
          ["file_dst_pathname"]=>
          string(55) "/var/www/html/bi/public/img/fotos/upl_574744be9bcc2.jpg"
          ["image_src_x"]=>
          int(910)
          ["image_src_y"]=>
          int(1364)
          ["image_src_bits"]=>
          int(8)
          ["image_src_pixels"]=>
          int(1241240)
          ["image_src_type"]=>
          string(3) "jpg"
          ["image_dst_x"]=>
          int(910)
          ["image_dst_y"]=>
          int(1364)
         */

        /*
         * id                     
          ,id_categoria
          ,id_licencia
          ,ancho_px
          ,alto_px
          ,peso_kb
          ,nombre_fichero
          ,nombre
          ,descripcion
          ,id_usuario_creacion
          ,fecha_creacion
          ,id_usuario_modificacion
          ,fecha_modificacion

         */
//        $this->_db->prepare("INSERT INTO imagen (id,nombre,nombre_fichero) VALUES (null, :nombre, :nombre_fichero)")
//                ->execute(
//                        array(
//                            ':nombre' => $nombre,
//                            ':nombre_fichero' => $imagen
//                        )
//        );
        $this->_db->prepare("INSERT INTO imagen (id,ancho_px,alto_px,peso_kb,nombre_fichero,nombre, id_licencia, id_categoria) VALUES (null, :ancho, :alto, :peso, :nombre_fichero, :nombre, :id_licencia, :id_categoria)")
                ->execute(
                        array(
                            ':ancho' => $imagen->image_src_x,
                            ':alto' => $imagen->image_src_y,
                            ':peso' => floor($imagen->file_src_size / 1024),
                            ':nombre' => $nombre,
                            ':nombre_fichero' => $imagen->file_dst_name,
                            ':id_licencia' => 1,
                            ':id_categoria' => 1,
                        )
        );
    }

}
