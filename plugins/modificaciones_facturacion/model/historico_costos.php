<?php
/**
 * Created by PhpStorm.
 * User: alberto
 * Date: 04/06/17
 * Time: 11:50 AM
 */

class historico_costos extends fs_model{

    /**
     * Esta función es llamada al crear una tabla.
     * Permite insertar valores en la tabla.
     *
     */
    public $id;
    public $fecha_compra;
    public $precio;
    public $proveedor;
    public $referencia;
    public $ultima_factura;



    public function __construct($a=FALSE)
    {
        parent::__construct('historico_costo', 'plugins/modificaciones_facturacion/');

        if( !isset(self::$column_list) )
        {
            self::$column_list = 'fecha_compra,precio,proveedor,referencia,ultima_factura';
        }

        if($a)
        {
            $this->id=$a['id'];
            $this->fecha_compra=Date('d-m-Y', strtotime($a['fecha_compra']));
            $this->precio=floatval($a['precio']);
            $this->proveedor=$a['proveedor'];
            $this->referencia = $a['referencia'];
            $this->ultima_factura=$a['ultima_factura'];
        }
        else
        {
            $this->fecha_compra=Date('d-m-Y');
            $this->precio=0;
            $this->proveedor=NULL;
            $this->referencia = NULL;
            $this->ultima_factura=NULL;

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
        if( !$this->exists )
        {
            if( $this->db->select("SELECT referencia FROM ".$this->table_name." WHERE referencia = ".$this->var2str($this->referencia)." AND precio = ".$this->var2str($this->precio).";") )
            {
                $this->exists = TRUE;
            }
        }

        return $this->exists;
    }

    /**
     * Esta función sirve tanto para insertar como para actualizar
     * los datos del objeto en la base de datos.
     */
    public function save()
    {
        if( $this->exists())
        {
            $sql = "UPDATE ".$this->table_name." SET fecha_compra = ".$this->var2str($this->fecha_compra).
                ", proveedor = ".$this->var2str($this->proveedor).
                ", referencia = ".$this->var2str($this->referencia).
                ", ultima_factura = ".$this->var2str($this->ultima_factura).
                " WHERE referencia = ".$this->var2str($this->referencia)." AND precio=".$this->var2str($this->precio).";";

                    }
        else
        {
            $sql = "INSERT INTO ".$this->table_name." (".self::$column_list.") VALUES (".
                $this->var2str($this->fecha_compra).",".
                $this->var2str($this->precio).",".
                $this->var2str($this->proveedor).",".
                $this->var2str($this->referencia).",".
                $this->var2str($this->ultima_factura).");";
        }

        if( $this->db->exec($sql) )
        {
            $this->exists = TRUE;
            return TRUE;
        }
        else
            return FALSE;
    }

    /**
     * Esta función sirve para eliminar los datos del objeto de la base de datos
     */

    public function delete()
    {
        // TODO: Implement delete() method.
    }
}