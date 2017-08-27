<?php

/**
 * @author Carlos García Gómez      neorazorx@gmail.com
 * @copyright 2015-2017, Carlos García Gómez. All Rights Reserved.
 * @copyright 2015-2017, Jorge Casal Lopez. All Rights Reserved.
 */

/**
 * Description of tpv_movimiento
 *
 * @author carlos
 */
class tpv_movimiento extends fs_model
{
   public $cantidad;
   public $codagente;
   public $descripcion;
   public $fecha;
   public $idtpv_arqueo;
   
   /**
    * Clave primaria.
    * @var type 
    */
   public $idtpv_movimiento;
   
   public function __construct($m = FALSE)
   {
      parent::__construct('tpv_movimientos', 'plugins/tpv_tactil/');
      if($m)
      {
         $this->idtpv_movimiento = $this->intval($m['idtpv_movimiento']);
         $this->idtpv_arqueo = $m['idtpv_arqueo'];
         $this->cantidad = floatval($m['cantidad']);
         $this->codagente = $m['codtpv_agente'];
         $this->descripcion = $m['descripcion'];
         $this->fecha = date('d-m-Y', strtotime($m['fecha']));
      }
      else
      {
         $this->idtpv_movimiento = NULL;
         $this->idtpv_arqueo = NULL;
         $this->cantidad = 0;
         $this->codagente = NULL;
         $this->descripcion = NULL;
         $this->fecha = date('d-m-Y');
      }
   }
   
   protected function install()
   {
      return '';
   }
   
   public function exists()
   {
      if( is_null($this->idtpv_movimiento) )
      {
         return FALSE;
      }
      else
      {
         return $this->db->select("SELECT * FROM tpv_movimientos WHERE idtpv_movimiento = ".$this->var2str($this->idtpv_movimiento).";");
      }
   }
   
   public function save()
   {
      if( $this->exists() )
      {
         $sql = "UPDATE tpv_movimientos SET idtpv_arqueo = ".$this->var2str($this->idtpv_arqueo).
                 ", cantidad = ".$this->var2str($this->cantidad).
                 ", codtpv_agente = ".$this->var2str($this->codagente).
                 ", descripcion = ".$this->var2str($this->descripcion).
                 ", fecha = ".$this->var2str($this->fecha).
                 " WHERE idtpv_movimiento = ".$this->var2str($this->idtpv_movimiento).";";
         
         return $this->db->exec($sql);
      }
      else
      {
         $sql = "INSERT INTO tpv_movimientos (idtpv_arqueo,cantidad,codtpv_agente,descripcion,fecha) VALUES (".
                 $this->var2str($this->idtpv_arqueo).",".
                 $this->var2str($this->cantidad).",".
                 $this->var2str($this->codagente).",".
                 $this->var2str($this->descripcion).",".
                 $this->var2str($this->fecha).");";
         
         if( $this->db->exec($sql) )
         {
            $this->idtpv_movimiento = $this->db->lastval();
            return TRUE;
         }
         else
            return FALSE;
      }
   }
   
   public function delete()
   {
      return $this->db->exec("DELETE FROM tpv_movimientos WHERE idtpv_movimiento = ".$this->var2str($this->idtpv_movimiento).";");
   }
   
   public function all_from_arqueo($ida)
   {
      $clist = array();
      
      $data = $this->db->select("SELECT * FROM tpv_movimientos WHERE idtpv_arqueo = ".$this->var2str($ida)." ORDER BY idtpv_movimiento DESC;");
      if($data)
      {
         foreach($data as $d)
            $clist[] = new tpv_movimiento($d);
      }
      
      return $clist;
   }
}
