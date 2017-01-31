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
    public $id_factura;
    public $monto;
    public $saldo;
    public $hora;

    public function __construct($a=FALSE)
    {
        parent::__construct('co_pagos', 'plugins/modificaciones_facturacion/');
        if($a)
        {
            $this->id_pagos = $a['idcotizacion'];
            $this->fecha = Date('d-m-Y', strtotime($a['fecha']));
            $this->id_factura = $a['id_factura'];
            $this->fecha = Date('d-m-Y', strtotime($a['fecha']));
            $this->hora = '00:00:00';
            if( !is_null($a['hora']) )
            {
                $this->hora = date('H:i:s', strtotime($a['hora']));
            }
            $this->monto = floatval($a['monto']);
            $this->saldo = floatval($a['saldo']);
        }
        else
        {
            $this->id_pagos = NULL;
            $this->id_factura = NULL;
            $this->monto = '';
            $this->saldo='';
            $this->fecha = Date('d-m-Y');
            $this->hora = Date('H:i:s');
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

            $sql = "INSERT INTO ".$this->table_name." (fecha,id_factura,monto,saldo) VALUES
                      (".$this->var2str($this->fecha)
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