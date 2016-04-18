<?php

/**
 * Created by PhpStorm.
 * User: alberto
 * Date: 15/04/16
 * Time: 06:16 PM
 */
class informe_stock  extends fs_controller
{
    public $inventario;
    public $offset;
    public function __construct()
    {
        parent::__construct(__CLASS__, 'Inventario', 'informes');

    }
    protected function private_core()
    {

        $this->offset=0;
        if(isset($_GET['offset'])){

        }
        $this->inventario=$this->db->select_limit("SELECT * FROM stocks ORDER BY referencia ASC ",5,5);

    }

}