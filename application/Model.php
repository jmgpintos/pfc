<?php

class Model
{

    protected $_db;

    public function __construct()
    {
        $this->_db = new Database();
    }

    public function getColumnas($data)
    {
        put(__METHOD__);
        if (count($data)) {
           return array_keys($data[0]);
        }else{
            return array();
        }
    }

}
