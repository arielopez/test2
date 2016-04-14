<?php
require_model('tarea_agenda.php');
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
    public $tarea_agenda;

    public function __construct()
    {
        parent::__construct(__CLASS__,'agenda','admin');
    }
    public function private_core()
    {
        $this->tarea_agenda= new tarea_agenda();
        $this->editar=FALSE;
        if(isset($_POST['fecha'])){ //nueva tarea
            $this->tarea_agenda->fecha=$_POST['fecha'].' '.$_POST['hora'];
            $this->tarea_agenda->usuario=$_POST['usuario'];
            $this->tarea_agenda->tarea=$_POST['tarea'];
            if($this->tarea_agenda->save()){
                $this->new_message('Se ha guardado correctamente');
            }
            else
                $this->new_error_msg('Error al guardar los datos');

        }
        elseif(isset($_POST['id'])){//actualizar y o editar tarea
            $this->editar=$this->tarea_agenda->getID($_POST['id']);
            if($this->editar){
                $this->editar->fecha=date('Y-m-d',strtotime($_POST['efecha'])).' '.$_POST['hora'];
                $this->editar->completado=isset($_POST['completado']);
                $this->editar->usuario=$_POST['usuario'];
                $this->editar->tarea=$_POST['tarea'];
                if($this->editar->save()){
                    $this->new_message('Se ha modificado correctamente');
                }else
                    $this->new_error_msg('Error al modificar los datos');
            }

        }
        elseif(isset($_GET['delete'])){ //eliminar tarea
            $sql="DELETE FROM agenda WHERE id='".$_GET['delete']."';";
            if($this->db->exec($sql)){
                $this->new_message("Tarea eliminada");
            }
            else
                $this->new_error_msg('La tarea no ha sido eliminada');

        }
        elseif(isset($_GET['id'])){ // desplegar agenda
            $this->editar=$this->tarea_agenda->getID($_GET['id']);
        }
            $this->listado=$this->tarea_agenda->all();

    }



}