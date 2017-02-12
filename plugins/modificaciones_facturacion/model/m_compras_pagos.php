<?php

/**
 * Created by PhpStorm.
 * User: alberto
 * Date: 31/01/17
 * Time: 01:01 PM
 */
class m_compras_pagos extends fs_model
{

    public $id_pagos;
    public $fecha;
    public $documento;
    public $id_factura;
    public $monto;
    public $saldo;
    public $hora;

    public function __construct($a=FALSE)
    {
        parent::__construct('co_pagos', 'plugins/modificaciones_facturacion/');
        if($a)
        {
            $this->id_pagos = $a['id_pagos'];
            $this->fecha = Date('d-m-Y', strtotime($a['fecha']));
            $this->id_factura = $a['id_factura'];
            $this->monto = floatval($a['monto']);
            $this->saldo = floatval($a['saldo']);
            $this->documento= $a['documento'];
        }
        else
        {
            $this->id_pagos = NULL;
            $this->id_factura = NULL;
            $this->documento=NULL;
            $this->monto = '';
            $this->saldo='';
            $this->fecha = Date('d-m-Y');
        }
    }

    public function all_from_pagos($id)
    {
        $linlist = array();

        $lineas = $this->db->select("SELECT * FROM ".$this->table_name." WHERE id_factura = ".$this->var2str($id)." ORDER BY id_pagos ASC;");
        if($lineas)
        {
            foreach($lineas as $l)
                $linlist[] = new m_compras_pagos($l);
        }

        return $linlist;
    }

    public function ultimo_pago($id)
    {

        $linea = $this->db->select("SELECT saldo FROM ".$this->table_name." WHERE id_factura = ".$this->var2str($id)." ORDER BY fecha DESC,saldo ASC LIMIT 1;");
        if($linea)
        {
            foreach($linea as $l)
                return $l['saldo'];
        }
        else{
            return 0;
        }

    }

    protected function install()
    {
        // TODO: Implement install() method.
    }

    /**
     * Esta función devuelve TRUE si los datos del objeto se encuentran
     * en la base de datos.
     */
    public function exists()
    {
        if( is_null($this->id_pagos) )
        {
            return FALSE;
        }
        else
            return $this->db->select("SELECT * FROM ".$this->table_name." WHERE id_pagos = ".$this->var2str($this->id_pagos).";");
    }

    /**
     * Esta función sirve tanto para insertar como para actualizar
     * los datos del objeto en la base de datos.
     */
    public function save()
    {
        if( $this->exists() )
        {
            $sql = "UPDATE ".$this->table_name." SET id_pagos = ".$this->var2str($this->id_pagos)
                .", fecha = ".$this->var2str($this->fecha)
                .", id_factura = ".$this->var2str($this->id_factura)
                .", monto = ".$this->var2str($this->monto)
                .", saldo = ".$this->var2str($this->saldo)


                ."  WHERE id_pagos = ".$this->var2str($this->id_pagos).";";

            return $this->db->exec($sql);
        }
        else
        {

            $sql = "INSERT INTO ".$this->table_name." (fecha,documento,id_factura,monto,saldo) VALUES
                      (".$this->var2str($this->fecha)
                .",".$this->var2str($this->documento)
                .",".$this->var2str($this->id_factura)
                .",".$this->var2str($this->monto)
                .",".$this->var2str($this->saldo).");";

            if( $this->db->exec($sql) )
            {
                $this->id_pagos = $this->db->lastval();
                return TRUE;
            }
            else
                return FALSE;
        }
    }

    /**
     * Esta función sirve para eliminar los datos del objeto de la base de datos
     */
    public function delete()
    {
        return $this->db->exec("DELETE FROM ".$this->table_name." WHERE id_pagos = ".$this->var2str($this->id_pagos).";");

    }
}