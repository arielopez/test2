
<?php
/*
 * This file is part of FacturaSctipts
 * Copyright (C) 2013-2015  Carlos Garcia Gomez  neorazorx@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_model('articulo.php');
require_model('cotizacion_proveedor.php');

/**
 * Línea de un albarán de proveedor (boceto de una factura).
 */
class linea_cotizacion_proveedor extends fs_model
{
   /**
    * Clave primaria.
    * @var type 
    */
   public $idlinea;
   
   /**
    * ID de la línea del pedido relacionada, si la hay.
    * @var type 
    */
   public $idlineapedido;
   
   /**
    * ID del albarán de esta línea.
    * @var type 
    */
   public $idcotizacion;
   
   /**
    * ID del pedido relacionado con el albarán, si lo hay.
    * @var type 
    */
   public $idpedido;
   
   /**
    * Referencia del artículo.
    * @var type 
    */
   public $referencia;
   public $descripcion;
   public $cantidad;
   
   /**
    * % de descuento.
    * @var type 
    */
   public $dtopor;
   
   /**
    * Impuesto relacionado.
    * @var type 
    */
   public $codimpuesto;
   
   /**
    * % de IVA del artículo, el que corresponde al impuesto.
    * @var type 
    */
   public $iva;
   
   /**
    * Importe neto de la línea, sin impuestos.
    * @var type 
    */
   public $pvptotal;
   
   /**
    * Importe neto sin descuentos.
    * @var type 
    */
   public $pvpsindto;
   
   /**
    * Precio del artículo, una unidad.
    * @var type 
    */
   public $pvpunitario;
   public $pvp_ivaincluido;
   
   /**
    * % de IRPF de la línea.
    * @var type 
    */
   public $irpf;
   
   /**
    * % de recargo de equivalencia de la línea.
    * @var type 
    */
   public $recargo;
   
   private $codigo;
   private $fecha;
   
   private static $cotizaciones;
   
   public function __construct($l=FALSE)
   {
      parent::__construct('lineascotizacion_prov', 'plugins/facturacion_base/');
      
      if( !isset(self::$cotizaciones) )
         self::$cotizaciones = array();
      
      if($l)
      {
         $this->idlinea = $this->intval($l['idlinea']);
         $this->idlineapedido = $this->intval($l['idlineapedido']);
         $this->idcotizacion = $this->intval($l['idcotizacion']);
         $this->idpedido = $this->intval($l['idpedido']);
         $this->referencia = $l['referencia'];
         $this->descripcion = $l['descripcion'];
         $this->cantidad = floatval($l['cantidad']);
         $this->dtopor = floatval($l['dtopor']);
         $this->codimpuesto = $l['codimpuesto'];
         $this->iva = floatval($l['iva']);
         $this->pvptotal = floatval($l['pvptotal']);
         $this->pvpsindto = floatval($l['pvpsindto']);
         $this->pvpunitario = floatval($l['pvpunitario']);
         $this->pvp_ivaincluido = floatval($l['pvp_ivaincluido']);
         $this->irpf = floatval($l['irpf']);
         $this->recargo = floatval($l['recargo']);
      }
      else
      {
         $this->idlinea = NULL;
         $this->idlineapedido = NULL;
         $this->idcotizacion = NULL;
         $this->idpedido = NULL;
         $this->referencia = NULL;
         $this->descripcion = '';
         $this->cantidad = 0;
         $this->dtopor = 0;
         $this->codimpuesto = NULL;
         $this->iva = 0;
         $this->pvptotal = 0;
         $this->pvpsindto = 0;
         $this->pvpunitario = 0;
         $this->pvp_ivaincluido = 0;
         $this->irpf = 0;
         $this->recargo = 0;
      }
   }

   protected function install()
   {
      return '';
   }
   
   private function fill()
   {
      $encontrado = FALSE;
      foreach(self::$cotizaciones as $a)
      {
         if($a->idcotizacion == $this->idcotizacion)
         {
            $this->codigo = $a->codigo;
            $this->fecha = $a->fecha;
            $encontrado = TRUE;
            break;
         }
      }
      if( !$encontrado )
      {
         $alb = new cotizacion_proveedor();
         $alb = $alb->get($this->idcotizacion);
         if( $alb )
         {
            $this->codigo = $alb->codigo;
            $this->fecha = $alb->fecha;
            self::$cotizaciones[] = $alb;
         }
      }
   }
   
   public function pvp_iva()
   {
      return $this->pvpunitario*(100+$this->iva)/100;
   }
   
   public function total_iva()
   {
      return $this->pvptotal*(100+$this->iva-$this->irpf+$this->recargo)/100;
   }
   
   /// Devuelve el precio total por unidad (con descuento incluido e iva aplicado)
   public function total_iva2()
   {
      if($this->cantidad == 0)
      {
         return 0;
      }
      else
         return $this->pvptotal*(100+$this->iva)/100/$this->cantidad;
   }
   
   public function show_codigo()
   {
      if( !isset($this->codigo) )
         $this->fill();
      return $this->codigo;
   }
   
   public function show_fecha()
   {
      if( !isset($this->fecha) )
         $this->fill();
      return $this->fecha;
   }
   
   public function show_nombre()
   {
      $nombre = 'desconocido';
      
      foreach(self::$cotizaciones as $a)
      {
         if($a->idcotizacion == $this->idcotizacion)
         {
            $nombre = $a->nombre;
            break;
         }
      }
      
      return $nombre;
   }
   
   public function url()
   {
      return 'index.php?page=compras_cotizacion&id='.$this->idcotizacion;
   }
   
   public function articulo_url()
   {
      if( is_null($this->referencia) OR $this->referencia == '')
      {
         return "index.php?page=ventas_articulos";
      }
      else
         return "index.php?page=ventas_articulo&ref=".urlencode($this->referencia);
   }
   
   public function exists()
   {
      if( is_null($this->idlinea) )
      {
         return false;
      }
      else
         return $this->db->select("SELECT * FROM ".$this->table_name." WHERE idlinea = ".$this->var2str($this->idlinea).";");
   }
   
   public function test()
   {
      $this->descripcion = $this->no_html($this->descripcion);
      $total = $this->pvpunitario * $this->cantidad * (100 - $this->dtopor) / 100;
      $totalsindto = $this->pvpunitario * $this->cantidad;
      
      if( !$this->floatcmp($this->pvptotal, $total, FS_NF0, TRUE) )
      {
         $this->new_error_msg("Error en el valor de pvptotal de la línea ".$this->referencia." del Cotizacion Valor correcto: ".$total);
         return FALSE;
      }
      else if( !$this->floatcmp($this->pvpsindto, $totalsindto, FS_NF0, TRUE) )
      {
         $this->new_error_msg("Error en el valor de pvpsindto de la línea ".$this->referencia." del Cotizacion Valor correcto: ".$totalsindto);
         return FALSE;
      }
      else
         return TRUE;
   }
   
   public function save()
   {
      if( $this->test() )
      {
         $this->clean_cache();
         
         if( $this->exists() )
         {
            $sql = "UPDATE ".$this->table_name." SET idcotizacion = ".$this->var2str($this->idcotizacion)
                    .", idpedido = ".$this->var2str($this->idpedido)
                    .", idlineapedido = ".$this->var2str($this->idlineapedido)
                    .", referencia = ".$this->var2str($this->referencia)
                    .", descripcion = ".$this->var2str($this->descripcion)
                    .", cantidad = ".$this->var2str($this->cantidad)
                    .", dtopor = ".$this->var2str($this->dtopor)
                    .", codimpuesto = ".$this->var2str($this->codimpuesto)
                    .", iva = ".$this->var2str($this->iva)
                    .", pvptotal = ".$this->var2str($this->pvptotal)
                    .", pvpsindto = ".$this->var2str($this->pvpsindto)
                    .", pvpunitario = ".$this->var2str($this->pvpunitario)
                    .", pvp_ivaincluido = ".$this->var2str($this->pvp_ivaincluido)
                    .", irpf = ".$this->var2str($this->irpf)
                    .", recargo = ".$this->var2str($this->recargo)
                    ."  WHERE idlinea = ".$this->var2str($this->idlinea).";";
            
            return $this->db->exec($sql);
         }
         else
         {
            $sql = "INSERT INTO ".$this->table_name." (idlineapedido,idcotizacion,idpedido,referencia,descripcion,
               cantidad,dtopor,codimpuesto,iva,pvptotal,pvpsindto,pvpunitario,pvp_ivaincluido, irpf,recargo) VALUES
                     (".$this->var2str($this->idlineapedido).
                    ",".$this->var2str($this->idcotizacion).
                    ",".$this->var2str($this->idpedido).
                    ",".$this->var2str($this->referencia).
                    ",".$this->var2str($this->descripcion).
                    ",".$this->var2str($this->cantidad).
                    ",".$this->var2str($this->dtopor).
                    ",".$this->var2str($this->codimpuesto).
                    ",".$this->var2str($this->iva).
                    ",".$this->var2str($this->pvptotal).
                    ",".$this->var2str($this->pvpsindto).
                    ",".$this->var2str($this->pvpunitario).
                    ",".$this->var2str($this->pvp_ivaincluido).
                    ",".$this->var2str($this->irpf).
                    ",".$this->var2str($this->recargo).");";
            
            if( $this->db->exec($sql) )
            {
               $this->idlinea = $this->db->lastval();
               return TRUE;
            }
            else
               return FALSE;
         }
      }
      else
         return FALSE;
   }
   
   public function delete()
   {
      $this->clean_cache();
      return $this->db->exec("DELETE FROM ".$this->table_name." WHERE idlinea = ".$this->var2str($this->idlinea).";");
   }
   
   public function clean_cache()
   {
      $this->cache->delete('albpro_top_articulos');
   }
   
   public function all_from_cotizacion($id)
   {
      $linealist = array();
      
      $lineas = $this->db->select("SELECT * FROM ".$this->table_name." WHERE idcotizacion = ".$this->var2str($id)." ORDER BY idlinea ASC;");
      if($lineas)
      {
         foreach($lineas as $l)
            $linealist[] = new linea_cotizacion_proveedor($l);
      }
      
      return $linealist;
   }
   
   public function all_from_articulo($ref, $offset=0, $limit=FS_ITEM_LIMIT)
   {
      $linealist = array();
      $lineas = $this->db->select_limit("SELECT * FROM ".$this->table_name.
              " WHERE referencia = ".$this->var2str($ref).
              " ORDER BY idcotizacion DESC", $limit, $offset);
      if( $lineas )
      {
         foreach($lineas as $l)
            $linealist[] = new linea_cotizacion_proveedor($l);
      }
      return $linealist;
   }
   
   public function search($query='', $offset=0)
   {
      $linealist = array();
      $query = strtolower( $this->no_html($query) );
      
      $sql = "SELECT * FROM ".$this->table_name." WHERE ";
      if( is_numeric($query) )
      {
         $sql .= "referencia LIKE '%".$query."%' OR descripcion LIKE '%".$query."%'";
      }
      else
      {
         $buscar = str_replace(' ', '%', $query);
         $sql .= "lower(referencia) LIKE '%".$buscar."%' OR lower(descripcion) LIKE '%".$buscar."%'";
      }
      $sql .= " ORDER BY idcotizacion DESC, idlinea ASC";
      
      $lineas = $this->db->select_limit($sql, FS_ITEM_LIMIT, $offset);
      if( $lineas )
      {
         foreach($lineas as $l)
            $linealist[] = new linea_cotizacion_proveedor($l);
      }
      return $linealist;
   }
   
   public function search_from_proveedor($codproveedor, $query='', $offset=0)
   {
      $linealist = array();
      $query = strtolower( $this->no_html($query) );
      
      $sql = "SELECT * FROM ".$this->table_name." WHERE idcotizacion IN
         (SELECT idcotizacion FROM cotizacion_prov WHERE codproveedor = ".$this->var2str($codproveedor).") AND ";
      if( is_numeric($query) )
      {
         $sql .= "(referencia LIKE '%".$query."%' OR descripcion LIKE '%".$query."%')";
      }
      else
      {
         $buscar = str_replace(' ', '%', $query);
         $sql .= "(lower(referencia) LIKE '%".$buscar."%' OR lower(descripcion) LIKE '%".$buscar."%')";
      }
      $sql .= " ORDER BY idcotizacion DESC, idlinea ASC";
      
      $lineas = $this->db->select_limit($sql, FS_ITEM_LIMIT, $offset);
      if( $lineas )
      {
         foreach($lineas as $l)
            $linealist[] = new linea_cotizacion_proveedor($l);
      }
      return $linealist;
   }
   
   public function count_by_articulo()
   {
      $lineas = $this->db->select("SELECT COUNT(DISTINCT referencia) as total FROM ".$this->table_name.";");
      if($lineas)
      {
         return intval($lineas[0]['total']);
      }
      else
         return 0;
   }
}
