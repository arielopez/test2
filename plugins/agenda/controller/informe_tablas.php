<?php

/**
 * Created by PhpStorm.
 * User: alberto
 * Date: 31/03/16
 * Time: 11:38 PM
 */
class informe_tablas extends fs_controller
{
    public $tablas;
    public $columnas;
    public function __construct()
    {
        parent::__construct(__CLASS__,'tablas','informes');
    }

    protected function private_core()
    {
        $this->columnas=FALSE;
        if (isset($_GET['table'])){
            $this->columnas=$this->db->get_columns($_GET['table']);

        }
        $this->tablas=$this->db->list_tables();
    }
}