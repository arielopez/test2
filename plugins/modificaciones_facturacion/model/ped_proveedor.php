<?php

require_model('proveedor.php');
require_model('ped_detalle.php');
require_model('secuencia.php');


class ped_proveedor  extends fs_model
{
    public $codigo;
    public $codagente;
    public $codpago;
    public $id_pedido;
    public $observaciones;
    public $codalmacen;
    public $numproveedor;
    public $ruc_ci;
    public $codproveedor;
    public $nombre;
    public $fecha;
    public $hora;
    public $codserie;
    public $numero;

    public function __construct($p=false)
    {
        parent::__construct('orden_compra_prov', 'plugins/modificaciones_facturacion/');
    }

    /**
     * Esta función es llamada al crear una tabla.
     * Permite insertar valores en la tabla.
     */
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
        // TODO: Implement exists() method.
    }

    /**
     * Esta función sirve tanto para insertar como para actualizar
     * los datos del objeto en la base de datos.
     */
    public function save()
    {
        // TODO: Implement save() method.
    }

    /**
     * Esta función sirve para eliminar los datos del objeto de la base de datos
     */
    public function delete()
    {
        // TODO: Implement delete() method.
    }
}