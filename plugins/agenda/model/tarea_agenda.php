<?php

/**
 * Created by PhpStorm.
 * User: alberto
 * Date: 10/04/16
 * Time: 10:31 PM
 */
class tarea_agenda extends fs_model
{
    public $id;
    public $fecha;
    public $usuario;
    public $tarea;
    public $completado;

    public function __construct($t=false)
    {
        parent::__construct('agenda', 'plugins/agenda/');
        if($t){
            $this->id=$t['id'];
            $this->fecha=Date('d-m-Y H:i', strtotime($t['fecha']));
            $this->usuario=$t['usuario'];
            $this->completado=$this->str2bool($t['completado']);
            $this->tarea=$t['tarea'];
        }
        else{
            $this->id=null;
            $this->fecha=Date('d-m-Y H:i');
            $this->usuario=null;
            $this->completado=false;
            $this->tarea='';
        }
    }
    public function separa_fecha(){

        $data=explode(' ',$this->fecha);
        $data[0]=date('d-m-Y',strtotime($data[0]));
        return $data[0];
    }
    public function separa_hora(){

        $data=explode(' ',$this->fecha);
        return $data[1];
    }
    /**
     * Esta función es llamada al crear una tabla.
     * Permite insertar valores en la tabla.
     */
    protected function install()
    {
        return '';// TODO: Implement install() method. que quieres que se ejecute cuando se crea la tabla
    }

    public function getID($id){
        $data= $this->db->select("SELECT * FROM agenda WHERE id=".$this->var2str($id).";");
        if($data){
            return new tarea_agenda($data[0]);
        }
        else
            return false;
    }
    /**
     * Esta función devuelve TRUE si los datos del objeto se encuentran
     * en la base de datos.
     */
    public function exists()
    {
        if(is_null($this->id)){
            return FALSE;
        }
        else
            return $this->db->select("SELECT * FROM agenda WHERE id=".$this->var2str($this->id).";");
    }

    /**
     * Esta función sirve tanto para insertar como para actualizar
     * los datos del objeto en la base de datos.
     */
    public function save()
    {
        if($this->exists()){
            $sql="UPDATE agenda SET fecha = ".$this->var2str($this->fecha)
                .", usuario= ".$this->var2str($this->usuario)
                .", completado= ".$this->var2str($this->completado)
                .", tarea =".$this->var2str($this->tarea)." where id= ".$this->var2str($this->id).";";
            return $this->db->exec($sql);
        }
        else{
            $sql="INSERT INTO agenda (fecha, usuario, tarea, completado) VALUES (".$this->var2str($this->fecha)
                .",".$this->var2str($this->usuario)
                .",".$this->var2str($this->tarea)
                .",".$this->var2str($this->completado)
                .")";
            if($this->db->exec($sql)){
                $this->id=$this->db->lastval();
                return true;
            }
            else
                return false;
        }
    }

    /**
     * Esta función sirve para eliminar los datos del objeto de la base de datos
     */
    public function delete()
    {
        return $this->db->exec("DELETE FROM agenda id = ".$this->var2str($this->id).";");
    }
    public function all(){
        $lista=array();
        $data= $this->db->select("SELECT * FROM agenda ORDER BY fecha DESC ;");
        if($data){
            foreach($data as $d){
                $lista[]=new tarea_agenda($d);
            }
        }
        return $lista;
    }
}