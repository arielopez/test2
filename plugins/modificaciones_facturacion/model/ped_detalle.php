<?php

class ped_detalle extends fs_model
{
    public $cantidad;
    public $descripcion;
    public $id_detalle;
    public $id_pedido;
    public $referencia;


    private static $pedidos_proveedor;
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