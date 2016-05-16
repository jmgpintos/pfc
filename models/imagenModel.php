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
class imagenModel extends Model{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        $post = $this->_db->query("SELECT * FROM imagen");
        
        return $post->fetchAll(PDO::FETCH_ASSOC);
    }
}
