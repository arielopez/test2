<?php

/**
 * @author Carlos García Gómez      neorazorx@gmail.com
 * @copyright 2015-2016, Carlos García Gómez. All Rights Reserved. 
 */

/**
 * Description of receta
 *
 * @author carlos
 */
class receta extends fs_model
{
   /**
    * Clave primaria. Varchar(25).
    * @var type 
    */
   public $codreceta;
   public $descripcion;
   
   /**
    * Referencia del artículo a producir.
    * @var type 
    */
   public $referencia;
   
   /**
    * Cantidad a producir.
    * @var type 
    */
   public $cantidad;
   
   /**
    * Código del almacén de los ingredientes.
    * @var type 
    */
   public $codalmacen;
   
   /**
    * Código del almacén del producto resultante.
    * @var type 
    */
   public $codalmacen2;
   
   public $ultima_produccion;
   public $observaciones;
   
   public function __construct($r = FALSE)
   {
      parent::__construct('recetas');
      if($r)
      {
         $this->codreceta = $r['codreceta'];
         $this->descripcion = $r['descripcion'];
         $this->referencia = $r['referencia'];
         $this->cantidad = floatval($r['cantidad']);
         $this->codalmacen = $r['codalmacen'];
         $this->codalmacen2 = $r['codalmacen2'];
         
         $this->ultima_produccion = NULL;
         if($r['ultima_produccion'])
         {
            $this->ultima_produccion = date('d-m-Y H:i:s', strtotime($r['ultima_produccion']));
         }
         
         $this->observaciones = $r['observaciones'];
      }
      else
      {
         $this->codreceta = NULL;
         $this->descripcion = NULL;
         $this->referencia = NULL;
         $this->cantidad = 1;
         $this->codalmacen = NULL;
         $this->codalmacen2 = NULL;
         $this->ultima_produccion = NULL;
         $this->observaciones = NULL;
      }
   }
   
   protected function install()
   {
      return '';
   }
   
   public function url()
   {
      return 'index.php?page=recetas&cod='.$this->codreceta;
   }
   
   public function get($cod)
   {
      $data = $this->db->select("SELECT * FROM recetas WHERE codreceta = ".$this->var2str($cod).";");
      if($data)
      {
         return new receta($data[0]);
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
         return $this->db->select("SELECT * FROM recetas WHERE codreceta = ".$this->var2str($this->codreceta).";");
   }
   
   public function save()
   {
      if( $this->exists() )
      {
         $sql = "UPDATE recetas SET descripcion = ".$this->var2str($this->descripcion).
                 ", referencia = ".$this->var2str($this->referencia).
                 ", cantidad = ".$this->var2str($this->cantidad).
                 ", codalmacen = ".$this->var2str($this->codalmacen).
                 ", codalmacen2 = ".$this->var2str($this->codalmacen2).
                 ", ultima_produccion = ".$this->var2str($this->ultima_produccion).
                 ", observaciones = ".$this->var2str($this->observaciones).
                 " WHERE codreceta = ".$this->var2str($this->codreceta).";";
      }
      else
      {
         $sql = "INSERT INTO recetas (codreceta,descripcion,referencia,cantidad,codalmacen,"
                 . "codalmacen2,ultima_produccion,observaciones) VALUES "
                 . "(".$this->var2str($this->codreceta)
                 . ",".$this->var2str($this->descripcion)
                 . ",".$this->var2str($this->referencia)
                 . ",".$this->var2str($this->cantidad)
                 . ",".$this->var2str($this->codalmacen)
                 . ",".$this->var2str($this->codalmacen2)
                 . ",".$this->var2str($this->ultima_produccion)
                 . ",".$this->var2str($this->observaciones).");";
      }
      
      return $this->db->exec($sql);
   }
   
   public function delete()
   {
      return $this->db->exec("DELETE FROM recetas WHERE codreceta = ".$this->var2str($this->codreceta).";");
   }
   
   public function all()
   {
      $relist = array();
      
      $data = $this->db->select("SELECT * FROM recetas ORDER BY codreceta ASC;");
      if($data)
      {
         foreach($data as $d)
         {
            $relist[] = new receta($d);
         }
      }
      
      return $relist;
   }
}
