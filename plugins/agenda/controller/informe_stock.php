<?php

/**
 * Created by PhpStorm.
 * User: alberto
 * Date: 31/03/16
 * Time: 11:43 AM
 */
class informe_stock extends fs_controller
{
    public $inventario;
    public $offset;
    public function __construct()
    {
        parent::__construct(__CLASS__,'inventario','informes');
    }
    protected function private_core()
    {
        $this->offset=0;
        if($_GET['offset']&&intval($_GET['offset'])>0){
            $this->offset=intval($_GET['offset']);
        }

        $this->inventario= $this->db->select_limit("select * from stocks ORDER BY referencia ASC ",8,$this->offset);
//        $this->template=FALSE;
//        echo var_dump($this->inventario);

    }
}