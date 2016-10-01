<?php
/*
 * This file is part of FacturaSctipts
 * Copyright (C) 2014-2015  Carlos Garcia Gomez  neorazorx@gmail.com
 * Copyright (C) 2014-2015  Francesc Pineda Segarra  shawe.ewahs@gmail.com
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

require_model('agente.php');
require_model('articulo.php');
require_model('proveedor.php');
require_model('pedido_proveedor.php');
require_model('articulo_proveedor.php');

class compras_pedidos extends fs_controller
{
   public $buscar_lineas;
   public $lineas;
   public $offset;
   public $resultados;
   public $resultados_stk_minimo;
   public $b_codfabricante;
   public $b_constock;
   public $b_publicos;
   public $b_bloqueados;
   public $b_orden;
   public $b_codtarifa;
   public $b_codfamilia;
   public $art_proveedor;

   public function __construct()
   {
      parent::__construct(__CLASS__, ucfirst(FS_PEDIDOS).' de compra', 'compras');
      
   }

   protected function private_core()
   {
      $pedido = new pedido_proveedor();

      $this->offset = 0;
      if( isset($_GET['offset']) )
      {
         $this->offset = intval($_GET['offset']);
      }
      if( isset($_GET['stock_min']) )
      {
         $this->search_articulos();
      }
      if( isset($_GET['art_proveedor']) )
      {
         $this->art_proveedor = $_GET['art_proveedor'];
      }
      if( isset($_POST['buscar_lineas']) )
      {
         $this->buscar_lineas();
      }
      else if( isset($_GET['codagente']) )
      {
         $this->template = 'extension/compras_pedidos_agente';

         $agente = new agente();
         $this->agente = $agente->get($_GET['codagente']);
         $this->resultados = $pedido->all_from_agente($_GET['codagente'], $this->offset);
      }
      else if( isset($_GET['codproveedor']) )
      {
         $this->template = 'extension/compras_pedidos_proveedor';

         $proveedor = new proveedor();
         $this->proveedor = $proveedor->get($_GET['codproveedor']);
         $this->resultados = $pedido->all_from_proveedor($_GET['codproveedor'], $this->offset);
      }
      else if( isset($_GET['ref']) )
      {
         $this->template = 'extension/compras_pedidos_articulo';

         $articulo = new articulo();
         $this->articulo = $articulo->get($_GET['ref']);

         $linea = new linea_pedido_proveedor();
         $this->resultados = $linea->all_from_articulo($_GET['ref'], $this->offset);
      }
      else
      {
         $this->share_extension();

         if( isset($_POST['delete']) )
         {
            $this->delete_pedido();
         }

         if($this->query)
         {
            $this->resultados = $pedido->search($this->query, $this->offset);
         }
         else if( isset($_GET['pendientes']) )
         {
            $this->resultados = $pedido->all_ptealbaran($this->offset);
         }
         else
         {
            /// ejecutamos el proceso del cron para pedidos.
            $pedido->cron_job();
            $this->resultados = $pedido->all($this->offset);
         }
      }
   }

   public function anterior_url()
   {
      $url = '';
      $extra = '';

      if( isset($_GET['ptealbaran']) )
      {
         $extra = '&ptealbaran=TRUE';
      }
      else if( isset($_GET['codagente']) )
      {
         $extra = '&codagente=' . $_GET['codagente'];
      }
      else if( isset($_GET['codproveedor']) )
      {
         $extra = '&codproveedor=' . $_GET['codproveedor'];
      }
      else if( isset($_GET['ref']) )
      {
         $extra = '&ref=' . $_GET['ref'];
      }

      if($this->query != '' AND $this->offset > '0')
      {
         $url = $this->url() . "&query=" . $this->query . "&offset=" . ($this->offset - FS_ITEM_LIMIT) . $extra;
      }
      else if($this->query == '' AND $this->offset > '0')
      {
         $url = $this->url() . "&offset=" . ($this->offset - FS_ITEM_LIMIT) . $extra;
      }

      return $url;
   }

   public function siguiente_url()
   {
      $url = '';
      $extra = '';

      if( isset($_GET['ptealbaran']) )
      {
         $extra = '&ptealbaran=TRUE';
      }
      else if( isset($_GET['codagente']) )
      {
         $extra = '&codagente=' . $_GET['codagente'];
      }
      else if( isset($_GET['codproveedor']) )
      {
         $extra = '&codproveedor=' . $_GET['codproveedor'];
      }
      else if( isset($_GET['ref']) )
      {
         $extra = '&ref=' . $_GET['ref'];
      }

      if($this->query != '' AND count($this->resultados) == FS_ITEM_LIMIT)
      {
         $url = $this->url() . "&query=" . $this->query . "&offset=" . ($this->offset + FS_ITEM_LIMIT) . $extra;
      }
      else if($this->query == '' AND count($this->resultados) == FS_ITEM_LIMIT)
      {
         $url = $this->url() . "&offset=" . ($this->offset + FS_ITEM_LIMIT) . $extra;
      }

      return $url;
   }

   public function buscar_lineas()
   {
      /// cambiamos la plantilla HTML
      $this->template = 'ajax/compras_lineas_pedidos';

      $this->buscar_lineas = $_POST['buscar_lineas'];
      $linea = new linea_pedido_proveedor();
      $this->lineas = $linea->search($this->buscar_lineas);
   }

   private function delete_pedido()
   {
      $ped = new pedido_proveedor();
      $ped1 = $ped->get($_POST['delete']);
      if($ped1)
      {
         if( $ped1->delete() )
         {
            $this->new_message(ucfirst(FS_PEDIDO) . ' ' . $ped1->codigo . " borrado correctamente.");
         }
         else
            $this->new_error_msg("¡Imposible borrar el " . FS_PEDIDO . "!");
      }
      else
         $this->new_error_msg("¡" . ucfirst(FS_PEDIDO) . " no encontrado!");
   }

   private function share_extension()
   {
      /// añadimos las extensiones para proveedors, agentes y artículos
      $extensiones = array(
          array(
              'name' => 'pedidos_proveedor',
              'page_from' => __CLASS__,
              'page_to' => 'compras_proveedor',
              'type' => 'button',
              'text' => '<span class="glyphicon glyphicon-list" aria-hidden="true"></span> &nbsp; '.ucfirst(FS_PEDIDOS),
              'params' => ''
          ),
          array(
              'name' => 'pedidos_agente',
              'page_from' => __CLASS__,
              'page_to' => 'admin_agente',
              'type' => 'button',
              'text' => '<span class="glyphicon glyphicon-list" aria-hidden="true"></span> &nbsp; '.ucfirst(FS_PEDIDOS) . ' a proveedor',
              'params' => ''
          ),
          array(
              'name' => 'pedidos_articulo',
              'page_from' => __CLASS__,
              'page_to' => 'ventas_articulo',
              'type' => 'tab_button',
              'text' => '<span class="glyphicon glyphicon-list" aria-hidden="true"></span> &nbsp; '.ucfirst(FS_PEDIDOS) . ' a proveedor',
              'params' => ''
          ),
      );
      foreach ($extensiones as $ext)
      {
         $fsext0 = new fs_extension($ext);
         if (!$fsext0->save())
         {
            $this->new_error_msg('Imposible guardar los datos de la extensión ' . $ext['name'] . '.');
         }
      }
   }
   
   public function total_pendientes()
   {
      $data = $this->db->select("SELECT COUNT(idpedido) as total FROM pedidosprov WHERE idalbaran IS NULL;");
      if($data)
      {
         return intval($data[0]['total']);
      }
      else
         return 0;
   }
   public function cant_stock_min()
   {
      $data = $this->db->select("SELECT COUNT(*) as total FROM articulos WHERE  stockmin > stockfis ;");
      if($data)
      {
         return intval($data[0]['total']);
      }
      else
         return 0;
   }
//busca los proveedores del articulo
   public function get_articulo_proveedores()
   {
      $artprov = new articulo_proveedor();
      $alist = $artprov->all_from_ref($this->art_proveedor);

      /// revismos el impuesto y la descripción
      foreach($alist as $i => $value)
      {
         $guardar = FALSE;
         if( is_null($value->codimpuesto) )
         {
            $alist[$i]->codimpuesto = $this->articulo->codimpuesto;
            $guardar = TRUE;
         }

         if( is_null($value->descripcion) )
         {
            $alist[$i]->descripcion = $this->articulo->descripcion;
            $guardar = TRUE;
         }

         if($guardar)
         {
            $alist[$i]->save();
         }
      }

      return $alist;
   }
   private function search_articulos()
   {
      $this->resultados_stk_minimo = array();
      $this->num_resultados = 0;
      $query = $this->empresa->no_html( strtolower($this->query) );
      $sql = ' FROM articulos ';
      $where = ' WHERE stockmin > stockfis and ';

      if($this->query != '')
      {
         $sql .= $where;
         if( is_numeric($query) )
         {
            $sql .= "(referencia = ".$this->empresa->var2str($query)
                . " OR referencia LIKE '%".$query."%'"
                . " OR equivalencia LIKE '%".$query."%'"
                . " OR descripcion LIKE '%".$query."%'"
                . " OR codbarras = '".$query."')";
         }
         else
         {
            $buscar = str_replace(' ', '%', $query);
            $sql .= "(lower(referencia) = ".$this->empresa->var2str($query)
                . " OR lower(referencia) LIKE '%".$buscar."%'"
                . " OR lower(equivalencia) LIKE '%".$buscar."%'"
                . " OR lower(descripcion) LIKE '%".$buscar."%')";
         }
         $where = ' AND ';
      }

      if($this->b_codfamilia != '')
      {
         $sql .= $where."codfamilia = ".$this->empresa->var2str($this->b_codfamilia);
         $where = ' AND ';
      }

      if($this->b_codfabricante != '')
      {
         $sql .= $where."codfabricante = ".$this->empresa->var2str($this->b_codfabricante);
         $where = ' AND ';
      }

      if($this->b_constock)
      {
         $sql .= $where."stockfis > 0";
         $where = ' AND ';
      }

      if($this->b_publicos)
      {
         $sql .= $where."publico = TRUE";
         $where = ' AND ';
      }

      if($this->b_bloqueados)
      {
         $sql .= $where."bloqueado = TRUE";
         $where = ' AND ';
      }
      else
      {
         $sql .= $where."bloqueado = FALSE";
         $where = ' AND ';
      }

      $order = 'referencia DESC';
      switch($this->b_orden)
      {
         case 'stockmin':
            $order = 'stockfis ASC';
            break;

         case 'stockmax':
            $order = 'stockfis DESC';
            break;

         case 'refmax':
            if( strtolower(FS_DB_TYPE) == 'postgresql' )
            {
               $order = 'referencia DESC';
            }
            else
            {
               $order = 'lower(referencia) DESC';
            }
            break;

         case 'descmin':
            $order = 'descripcion ASC';
            break;

         case 'descmax':
            $order = 'descripcion DESC';
            break;

         case 'preciomin':
            /// cogemos datos de la tarifa para ordenar:
            $tarifa = $this->tarifa->get($this->b_codtarifa);
            if($tarifa)
            {
               if($tarifa->aplicar_a == 'coste')
               {
                  $order = 'preciocoste ASC';
               }
               else
               {
                  $order = 'pvp ASC';
               }
            }
            else
            {
               $order = 'pvp ASC';
            }
            break;

         case 'preciomax':
            /// cogemos datos de la tarifa para ordenar:
            $tarifa = $this->tarifa->get($this->b_codtarifa);
            if($tarifa)
            {
               if($tarifa->aplicar_a == 'coste')
               {
                  $order = 'preciocoste DESC';
               }
               else
               {
                  $order = 'pvp DESC';
               }
            }
            else
            {
               $order = 'pvp DESC';
            }
            break;

         default:
         case 'refmin':
            if( strtolower(FS_DB_TYPE) == 'postgresql' )
            {
               $order = 'referencia ASC';
            }
            else
            {
               $order = 'lower(referencia) ASC';
            }
            break;
      }

      $data = $this->db->select("SELECT COUNT(referencia) as total".$sql);
      if($data)
      {
         $this->total_resultados = intval($data[0]['total']);

         /// ¿Descargar o mostrar en pantalla?
         if( isset($_GET['download']) )
         {
            /// desactivamos el motor de plantillas
            $this->template = FALSE;

            header("content-type:application/csv;charset=UTF-8");
            header("Content-Disposition: attachment; filename=\"articulos.csv\"");
            echo "referencia;codfamilia;codfabricante;descripcion;pvp;iva;codbarras;stock;coste\n";

            $offset2 = 0;
            $data2 = $this->db->select_limit("SELECT *".$sql." ORDER BY ".$order, 1000, $offset2);
            while($data2)
            {
               $resultados = array();
               foreach($data2 as $i)
               {
                  $resultados[] = new articulo($i);
               }

               if($this->b_codtarifa != '')
               {
                  /// aplicamos la tarifa
                  $tarifa = $this->tarifa->get($this->b_codtarifa);
                  if($tarifa)
                  {
                     $tarifa->set_precios($resultados);

                     /// si la tarifa añade descuento, lo aplicamos al precio
                     foreach($resultados as $i => $value)
                     {
                        $resultados[$i]->pvp -= $value->pvp*$value->dtopor/100;
                     }
                  }
               }

               /// escribimos los datos de los artículos
               foreach($resultados as $art)
               {
                  echo $art->referencia.';';
                  echo $art->codfamilia.';';
                  echo $art->codfabricante.';';
                  echo $this->fix_html($art->descripcion).';';
                  echo $art->pvp.';';
                  echo $art->get_iva().';';
                  echo trim($art->codbarras).';';
                  echo $art->stockfis.';';
                  echo $art->preciocoste()."\n";

                  $offset2++;
               }

               $data2 = $this->db->select_limit("SELECT *".$sql." ORDER BY ".$order, 1000, $offset2);
            }
         }
         else
         {
            $data2 = $this->db->select_limit("SELECT *".$sql." ORDER BY ".$order, FS_ITEM_LIMIT, $this->offset);
            if($data2)
            {
               foreach($data2 as $i)
               {
                  $this->resultados_stk_minimo[] = new articulo($i);
               }

               if($this->b_codtarifa != '')
               {
                  /// aplicamos la tarifa
                  $tarifa = $this->tarifa->get($this->b_codtarifa);
                  if($tarifa)
                  {
                     $tarifa->set_precios($this->resultados_stk_minimo);

                     /// si la tarifa añade descuento, lo aplicamos al precio
                     foreach($this->resultados_stk_minimo as $i => $value)
                     {
                        $this->resultados_stk_minimo[$i]->pvp -= $value->pvp*$value->dtopor/100;
                     }
                  }
               }
            }
         }
      }
   }
}
