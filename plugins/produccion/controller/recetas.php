<?php

/**
 * @author Carlos García Gómez      neorazorx@gmail.com
 * @copyright 2015-2017, Carlos García Gómez. All Rights Reserved. 
 */

require_model('almacen.php');
require_model('articulo.php');
require_model('receta.php');
require_model('linea_receta.php');
require_model('regularizacion_stock.php');

/**
 * Description of recetas
 *
 * @author carlos
 */
class recetas extends fs_controller
{
   public $allow_delete;
   public $almacen;
   public $articulo_resultado;
   public $lineas;
   public $receta;
   public $receta2;
   
   public function __construct()
   {
      parent::__construct(__CLASS__, 'Producción', 'Producción');
   }
   
   protected function private_core()
   {
      /// ¿El usuario tiene permiso para eliminar en esta página?
      $this->allow_delete = $this->user->allow_delete_on(__CLASS__);
      
      $this->almacen = new almacen();
      $this->articulo_resultado = FALSE;
      $art0 = new articulo();
      $this->lineas = array();
      $this->receta = new receta();
      $this->receta2 = FALSE;
      
      if( isset($_REQUEST['buscar_referencia']) )
      {
         $this->buscar_referencia();
      }
      else if( isset($_POST['ncodreceta']) )
      {
         $this->nueva_receta($art0);
      }
      else if( isset($_REQUEST['cod']) )
      {
         /// ver/modificar receta
         $this->template = 'receta';
         
         $this->receta2 = $this->receta->get($_REQUEST['cod']);
         if($this->receta2)
         {
            $linear = new linea_receta();
            
            if( isset($_POST['descripcion']) )
            {
               $this->receta2->descripcion = $_POST['descripcion'];
               $this->receta2->referencia = $_POST['referencia'];
               $this->receta2->cantidad = floatval($_POST['cantidad']);
               $this->receta2->codalmacen = $_POST['codalmacen'];
               $this->receta2->codalmacen2 = $_POST['codalmacen2'];
               $this->receta2->observaciones = $_POST['observaciones'];
               
               if( $this->receta2->save() )
               {
                  $this->new_message('Producto modificado correctamente');
               }
               else
                  $this->new_error_msg('Error al modificar la receta.');
            }
            else if( isset($_POST['idlinea']) )
            {
               if($_POST['idlinea'] == '-1')
               {
                  $referencia = $_POST['referencia'];
                  if($referencia == '')
                  {
                     $referencia = $_POST['ac_referencia'];
                  }
                  
                  /// nueva linea
                  $linear->codreceta = $this->receta2->codreceta;
                  $linear->referencia = $referencia;
                  $linear->cantidad = floatval($_POST['cantidad']);
                  if( $linear->save() )
                  {
                     $this->new_message('Componente añadido correctamente.');
                     
                     $articulo = $art0->get($referencia);
                     if(!$articulo)
                     {
                        if( isset($_POST['crear']) )
                        {
                           $articulo = new articulo();
                           $articulo->referencia = $referencia;
                           $articulo->descripcion = $referencia;
                           if( $articulo->save() )
                           {
                              $this->new_message('Artículo creado correctamente.');
                           }
                           else
                              $this->new_error_msg('Error al crear el artículo.');
                        }
                        else
                           $this->new_error_msg('El artículo no existe.');
                     }
                  }
                  else
                     $this->new_error_msg('Error al añadir el Componente.');
               }
               else
               {
                  /// modificar la linea
                  $linea2 = $linear->get($_POST['idlinea']);
                  if($linea2)
                  {
                     $linea2->referencia = $_POST['referencia'];
                     $linea2->cantidad = floatval($_POST['cantidad']);
                     if( $linea2->save() )
                     {
                        $this->new_message('Componente modificado correctamente.');
                     }
                     else
                        $this->new_error_msg('Error al añadir el Componente.');
                  }
               }
            }
            else if( isset($_GET['deletel']) )
            {
               /// eliminar la linea
               $linea2 = $linear->get($_GET['deletel']);
               if($linea2)
               {
                  if( $linea2->delete() )
                  {
                     $this->new_message('Componente eliminado correctamente.');
                  }
               }
            }
            
            $this->articulo_resultado = $art0->get($this->receta2->referencia);
            $this->lineas = $linear->all_from_receta($this->receta2->codreceta);
            
            if( isset($_POST['cocinar']) )
            {
               $this->cocinar();
            }
         }
         else
            $this->new_error_msg('Producto no encontrada.');
      }
      else if( isset($_GET['delete']) )
      {
         /// eliminar receta
         $receta = $this->receta->get($_GET['delete']);
         if($receta)
         {
            if( $receta->delete() )
            {
               $this->new_message('Producto eliminado correctamente.');
            }
            else
               $this->new_error_msg('Error al eliminar la receta.');
         }
         else
            $this->new_error_msg('Producto no encontrado.');
      }
   }
   
   private function nueva_receta(&$art0)
   {
      $referencia = $_POST['referencia'];
      if($referencia == '')
      {
         $referencia = $_POST['ac_referencia'];
      }
      
      $continuar = FALSE;
      
      $this->articulo_resultado = $art0->get($referencia);
      if($this->articulo_resultado)
      {
         $continuar = TRUE;
      }
      else if( isset($_POST['crear']) )
      {
         $this->articulo_resultado = new articulo();
         $this->articulo_resultado->referencia = $referencia;
         $this->articulo_resultado->descripcion = $_POST['descripcion'];
         if( $this->articulo_resultado->save() )
         {
            $continuar = TRUE;
         }
         else
            $this->new_error_msg('Error al crear el artículo.');
      }
      else
         $this->new_error_msg('El artículo no existe.');
      
      if($continuar)
      {
         $this->receta2 = new receta();
         $this->receta2->codreceta = $_POST['ncodreceta'];
         $this->receta2->descripcion = $_POST['descripcion'];
         $this->receta2->referencia = $referencia;
         $this->receta2->codalmacen = $this->empresa->codalmacen;
         $this->receta2->codalmacen2 = $this->empresa->codalmacen;
         
         if( $this->receta2->save() )
         {
            $this->new_message('Producto creada correctamente');
            $this->template = 'receta';
         }
         else
            $this->new_error_msg('Error al crear la receta.');
      }
   }
   
   private function cocinar()
   {
      /// ¿Hay suficiente stock?
      $multiplicador = intval($_POST['cocinar']);
      $cantidad = $this->receta2->cantidad*$multiplicador;
      $coste = 0;
      
      $continuar = TRUE;
      foreach($this->lineas as $l)
      {
         $coste += $l->coste()*$l->cantidad;
         
         if( $l->nostock() )
         {
            /// no hace falta comprobar el stock
         }
         else if($l->stock() < $l->cantidad*$multiplicador)
         {
            $continuar = FALSE;
            break;
         }
      }
      
      if($continuar)
      {
         $this->receta2->ultima_produccion = date('d-m-Y H:i:s');
         $this->receta2->save();
         
         /// escribimos en el historial
         $fslog = new fs_log();
         $fslog->usuario = $this->user->nick;
         $fslog->ip = $this->user->last_ip;
         $fslog->tipo = 'produccion';
         $fslog->detalle = 'Producción: '.$cantidad." unidades.\nMotivo: ".$_POST['motivo'];
         $fslog->save();
         
         foreach($this->lineas as $l)
         {
            $articulo = $this->articulo_resultado->get($l->referencia);
            if($articulo)
            {
               foreach($articulo->get_stock() as $stock)
               {
                  /// guardamos una regularización de stock para que quede constancia del cambio
                  $restock = new regularizacion_stock();
                  $restock->idstock = $stock->idstock;
                  $restock->cantidadini = $stock->cantidad;
                  
                  $articulo->sum_stock($this->receta2->codalmacen, 0 - $l->cantidad*$multiplicador);
                  $l->rellenar();
                  
                  $restock->cantidadfin = $l->stock();
                  $restock->codalmacendest = $this->receta2->codalmacen;
                  $restock->motivo = 'Producción: '.$cantidad." unidades.\nMotivo: ".$_POST['motivo'];
                  $restock->nick = $this->user->nick;
                  $restock->save();
                  break;
               }
            }
         }
         
         $this->articulo_resultado->preciocoste = $this->articulo_resultado->costemedio = $coste / $this->receta2->cantidad;
         $this->articulo_resultado->sum_stock($this->receta2->codalmacen2, $this->receta2->cantidad*$multiplicador);
         $this->new_message('Se han producido los artículos correctamente.');
      }
      else
         $this->new_error_msg('No hay suficiente stock.');
   }
   
   private function buscar_referencia()
   {
      /// desactivamos la plantilla HTML
      $this->template = FALSE;
      
      $articulo = new articulo();
      $json = array();
      foreach($articulo->search($_REQUEST['buscar_referencia']) as $art)
      {
         $json[] = array('value' => $art->referencia.': '.$art->descripcion, 'data' => $art);
      }
      
      header('Content-Type: application/json');
      echo json_encode( array('query' => $_REQUEST['buscar_referencia'], 'suggestions' => $json) );
   }
   
   public function historial()
   {
      $lista = array();
      
      if($this->receta2)
      {
         $fslog = new fs_log();
         $lista = $fslog->all_by('produccion');
      }
      
      return $lista;
   }
   
   public function total_producir()
   {
      $total = 0;
      
      for($i = 100; $i > 0; $i--)
      {
         $continuar = TRUE;
         foreach($this->lineas as $l)
         {
            if( $l->nostock() )
            {
               /// no hace falta comprobar el stock
            }
            else if($l->stock() < $l->cantidad*$i)
            {
               $continuar = FALSE;
               break;
            }
         }
         
         if($continuar)
         {
            $total = $i;
            break;
         }
      }
      
      return $total;
   }
}
