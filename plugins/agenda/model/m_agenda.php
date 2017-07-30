<?php

/**
 * Created by PhpStorm.
 * User: alberto
 * Date: 23/07/16
 * Time: 02:06 PM
 */
class m_agenda extends fs_model
{
    public $id_agenda;
    public $asignado;
    public $fecha_creacion;
    public $fecha_culminacion;
    public $creador;
    public $detalle;

    public function __construct($a=FALSE)
    {
        parent::__construct('agendaxml','plugins/agenda/');
        if($a){

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