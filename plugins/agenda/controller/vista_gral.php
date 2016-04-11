<?php
/**
 * Created by PhpStorm.
 * User: expber
 * Date: 05/04/2016
 * Time: 12:29 PM
 */

class vista_gral extends fs_controller{

    public function __construct(){
        parent::__construct(__CLASS__,'Vista General','Administracion');
    }
    protected function private_core()
    {
        parent::private_core();
    }

}