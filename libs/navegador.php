<?php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Navegador
 *
 * @author tatooine
 */
class Navegador
{
    private $_model;
    private $_navegador;
    private $_tabla;
    private $_id;

    public function __construct($tabla, $id)
    {
        $this->_model = new Model();;
        $this->_navegador = array();
        $this->_tabla = $tabla;
        $this->_id = $id;
    }

    public function navegar()
    {
        $this->_navegador = array(
            'primero' => array(
                'num' => ($this->_id == $this->_model->primero($this->_tabla)) ? "" : $this->_model->primero($this->_tabla),
                'estilo' => ($this->_id == $this->_model->primero($this->_tabla)) ? "disabled" : "",
            ),
            'anterior' => array(
                'num' => ($this->_id == $this->_model->primero($this->_tabla)) ? "" : $this->_id - 1,
                'estilo' => ($this->_id == $this->_model->primero($this->_tabla)) ? "disabled" : "",
            ),
            'siguiente' => array(
                'num' => ($this->_id == $this->_model->ultimo($this->_tabla)) ? "" : $this->_id + 1,
                'estilo' => ($this->_id == $this->_model->ultimo($this->_tabla)) ? "disabled" : "",
            ),
            'ultimo' => array(
                'num' => ($this->_id == $this->_model->ultimo($this->_tabla)) ? "" : $this->_model->ultimo($this->_tabla),
                'estilo' => ($this->_id == $this->_model->ultimo($this->_tabla)) ? "disabled" : "",
            ),
        );
        return $this->_navegador;
    }

}
