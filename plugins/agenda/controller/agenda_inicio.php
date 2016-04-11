<?php

/**
 * Created by PhpStorm.
 * User: alberto
 * Date: 04/04/16
 * Time: 09:38 AM
 */
class agenda_inicio extends fs_controller
{
    public $listado;
    public $editar;
    public function __construct()
    {
        parent::__construct(__CLASS__,'agenda','admin');
    }
    public function private_core()
    {

        $this->editar=FALSE;
        if(isset($_POST['fecha'])){ //nueva tarea
            $fecha=date('Y-m-d',strtotime($_POST['fecha']));
            $fecha.=' '.$_POST['hora'];
            $sql="INSERT INTO agenda (fecha,usuario,tarea) VALUES ('".$fecha."','"
                .$_POST['usuario']."','".$_POST['tarea']."')";

            if($this->db->exec($sql)){
                $this->new_message('Se ha guardado correctamente');
            }
            else
                $this->new_error_msg('Error al guardar los datos');

        }
        elseif(isset($_POST['id'])){//actualizar y o editar tarea
            $fecha=date('Y-m-d',strtotime($_POST['efecha']));
            $fecha.=' '.$_POST['hora'];
            $this->editar=$this->db->select("SELECT * FROM agenda WHERE id='".$_POST['id']."';");
            $completado=0;
            if(isset($_POST['completado']))
                $completado=1;

            if($this->editar){
                $sql="UPDATE agenda SET fecha='".$this->db->escape_string($fecha)."', usuario='".$_POST['usuario']
                    ."',tarea='".$this->db->escape_string(htmlentities($_POST['tarea']))
                    ."',completado='".$completado
                    ."' WHERE id='".$this->db->escape_string($_POST['id'])."';";
                if($this->db->exec($sql)){
                    $this->new_message('Se ha modificado correctamente');
                    $this->editar=$this->db->select("SELECT * FROM agenda WHERE id='".$this->db->escape_string($_POST['id'])."';");
                }else
                    $this->new_error_msg('Error al modificar los datos');
            }

        }elseif(isset($_GET['delete'])){ //eliminar tarea
            $sql="DELETE FROM agenda WHERE id='".$_GET['delete']."';";
            if($this->db->exec($sql)){
                $this->new_message("Tarea eliminada");
            }
            else
                $this->new_error_msg('La tarea no ha sido eliminada');

        }

        elseif(isset($_GET['id'])){ // desplegar agenda
            $this->editar=$this->db->select("SELECT * FROM agenda WHERE id='".$_GET['id']."';");
        }

            $this->listado=$this->db->select("SELECT * FROM agenda;");

    }

    public function separa_fecha(){

        $data=explode(' ',$this->editar[0]['fecha']);
        $data[0]=date('d-m-Y',strtotime($data[0]));
        return $data[0];
    }
    public function separa_hora(){

        $data=explode(' ',$this->editar[0]['fecha']);
        return $data[1];
    }

}