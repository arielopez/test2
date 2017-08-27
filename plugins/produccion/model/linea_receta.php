<?php

/**
 * @author Carlos García Gómez      neorazorx@gmail.com
 * @copyright 2015-2017, Carlos García Gómez. All Rights Reserved. 
 */

require_model('articulo.php');
require_model('receta.php');
require_model('stock.php');

/**
 * Description of linea_receta
 *
 * @author carlos
 */
class linea_receta extends fs_model
{
   public $idlinea;
   public $codreceta;
   public $cantidad;
   public $referencia;
   
   private $coste;
   private $descripcion;
   private $nostock;
   private $stock;
   
   public function __construct($l = FALSE)
   {
      parent::__construct('lineasrecetas');
      if($l)
      {
         $this->idlinea = $this->intval($l['idlinea']);
         $this->codreceta = $l['codreceta'];
         $this->cantidad = floatval($l['cantidad']);
         $this->referencia = $l['referencia'];
      }
      else
      {
         $this->idlinea = NULL;
         $this->codreceta = NULL;
         $this->cantidad = 1;
         $this->referencia = NULL;
      }
   }
   
   protected function install()
   {
      return '';
   }
   
   public function rellenar()
   {
      $this->coste = 0;
      $this->descripcion = '';
      $this->nostock = FALSE;
      $this->stock = 0;
      
      $rec0 = new receta();
      $receta = $rec0->get($this->codreceta);
      if($receta)
      {
         $art0 = new articulo();
         $articulo = $art0->get($this->referencia);
         if($articulo)
         {
            $this->coste = $articulo->get_precio_con_iva();
            $this->descripcion = $articulo->descripcion;
            $this->nostock = $articulo->nostock;
         }
         
         $stock = new stock();
         foreach($stock->all_from_articulo($this->referencia) as $s)
         {
            if($s->codalmacen == $receta->codalmacen)
            {
               $this->stock = $s->cantidad;
               break;
            }
         }
      }
   }
   
   public function coste()
   {
      if( !isset($this->coste) )
      {
         $this->rellenar();
      }
      
      return $this->coste;
   }
   
   public function descripcion()
   {
      if( !isset($this->descripcion) )
      {
         $this->rellenar();
      }
      
      return $this->descripcion;
   }
   
   public function stock()
   {
      if( !isset($this->stock) )
      {
         $this->rellenar();
      }
      
      return $this->stock;
   }
   
   public function nostock()
   {
      if( !isset($this->nostock) )
      {
         $this->rellenar();
      }
      
      return $this->nostock;
   }
   
   public function get($id)
   {
      $data = $this->db->select("SELECT * FROM lineasrecetas WHERE idlinea = ".$this->var2str($id).";");
      if($data)
      {
         return new linea_receta($data[0]);
      }
      else
         return FALSE;
   }
   
   public function exists()
   {
      if( is_null($this->codreceta) )
      {
         return FALSE;
      }
      else
         return $this->db->select("SELECT * FROM lineasrecetas WHERE idlinea = ".$this->var2str($this->idlinea).";");
   }
   
   public function save()
   {
      if( $this->exists() )
      {
         $sql = "UPDATE lineasrecetas SET referencia = ".$this->var2str($this->referencia)
                 .", cantidad = ".$this->var2str($this->cantidad)
                 ."  WHERE idlinea = ".$this->var2str($this->idlinea).";";
         
         return $this->db->exec($sql);
      }
      else
      {
         $sql = "INSERT INTO lineasrecetas (codreceta,referencia,cantidad) VALUES "
                 . "(".$this->var2str($this->codreceta)
                 . ",".$this->var2str($this->referencia)
                 . ",".$this->var2str($this->cantidad).");";
         
         if( $this->db->exec($sql) )
         {
            $this->idlinea = $this->db->lastval();
            return TRUE;
         }
         else
            return FALSE;
      }
   }
   
   public function delete()
   {
      return $this->db->exec("DELETE FROM lineasrecetas WHERE idlinea = ".$this->var2str($this->idlinea).";");
   }
   
   public function all_from_receta($cod)
   {
      $relist = array();
      $sql = "SELECT * FROM lineasrecetas WHERE codreceta = ".$this->var2str($cod)
              ." ORDER BY idlinea ASC;";
      
      $data = $this->db->select($sql);
      if($data)
      {
         foreach($data as $d)
         {
            $relist[] = new linea_receta($d);
         }
      }
      
      return $relist;
   }
}
