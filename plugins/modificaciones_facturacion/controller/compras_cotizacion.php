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
require_model('cotizacion_proveedor.php');
require_model('albaran_proveedor.php');
require_model('articulo.php');
require_model('asiento.php');
require_model('asiento_factura.php');
require_model('divisa.php');
require_model('ejercicio.php');
require_model('fabricante.php');
require_model('orden_compra_proveedor.php');
require_model('familia.php');
require_model('forma_pago.php');
require_model('impuesto.php');
require_model('partida.php');
require_model('proveedor.php');
require_model('regularizacion_iva.php');
require_model('serie.php');
require_model('subcuenta.php');

class compras_cotizacion extends fs_controller
{
   public $agente;
   public $cotizacion;
   public $allow_delete;
   public $divisa;
   public $ejercicio;
   public $fabricante;
   public $familia;
   public $forma_pago;
   public $impuesto;
   public $nueva_cotizacion_url;
   public $proveedor;
   public $proveedor_s;
   public $serie;
   
   public function __construct()
   {
      parent::__construct(__CLASS__, 'Cotizacion de proveedor', 'compras', FALSE, FALSE);
   }
   
   protected function private_core()
   {
      $this->ppage = $this->page->get('compras_cotizaciones');
      $this->agente = FALSE;
      
      $cotizacion = new cotizacion_proveedor();
      $this->cotizacion = FALSE;
      $this->divisa = new divisa();
      $this->ejercicio = new ejercicio();
      $this->fabricante = new fabricante();
      $this->familia = new familia();
      $this->forma_pago = new forma_pago();
      $this->impuesto = new impuesto();
      $this->proveedor = new proveedor();
      $this->proveedor_s = FALSE;
      $this->serie = new serie();
      
      /// ¿El usuario tiene permiso para eliminar en esta página?
      $this->allow_delete = $this->user->allow_delete_on(__CLASS__);
      
      /// comprobamos si el usuario tiene acceso a nueva_compra
      $this->nueva_cotizacion_url = FALSE;
      if( $this->user->have_access_to('nueva_compra', FALSE) )
      {
         $nuevoalbp = $this->page->get('nueva_compra');
         if($nuevoalbp)
            $this->nueva_cotizacion_url = $nuevoalbp->url();
      }
//      print_r($_POST);
      if(isset($_GET['his_referencia'])){
         $this->template = FALSE;
         $ultimas_compras=$this->search_ultima_compra($_GET['his_referencia']);
         $result="";
         if($ultimas_compras){
            foreach( $ultimas_compras as $i=>$compra){
               $result=$result."<tr><td scope='row'>".($i+1)."</td><td>".$compra['fecha']."</td><td>".round($compra['precio'],2)."</td><td>".$compra['proveedor']."</td><td>".$compra['factura']."</td></tr>";
            }
         }
         else
            $result="<tr><td colspan='5' class='success'>Sin compras registradas..<td><tr>";
         header('Content-Type: application/json');
         echo json_encode($result);
      }

      if( isset($_POST['idcotizacion']) )
      {
         $this->cotizacion = $cotizacion->get($_POST['idcotizacion']);
         $this->modificar();
      }
      else if( isset($_GET['id']) )
      {
         $this->cotizacion = $cotizacion->get($_GET['id']);
      }
      
      if($this->cotizacion)
      {
         $this->page->title = $this->cotizacion->codigo;
         
         /// cargamos el agente
         if( !is_null($this->cotizacion->codagente) )
         {
            $agente = new agente();
            $this->agente = $agente->get($this->cotizacion->codagente);
         }
         
         /// cargamos el proveedor
         $this->proveedor_s = $this->proveedor->get($this->cotizacion->codproveedor);
         
         /// comprobamos el albarán
         $this->cotizacion->full_test();
         
         if( isset($_GET['facturar']) AND isset($_GET['petid']) AND $this->cotizacion->ptefactura )
         {
            if( $this->duplicated_petition($_GET['petid']) )
            {
               $this->new_error_msg('Petición duplicada. Evita hacer doble clic sobre los botones.');
            }
            else
               $this->generar_orden_compra();
         }
         else if( isset($_GET['forze_fecha']) )
         {
            $this->forzar_fecha();
         }
      }
      else
         $this->new_error_msg("¡Cotizacion de proveedor no encontrado!");
   }


   public function search_ultima_compra($ref){
      $consulta = "SELECT fp.fecha, lf.pvpunitario precio, fp.nombre proveedor, lf.referencia,fp.codigo factura
                      FROM lineasfacturasprov lf
                      INNER JOIN facturasprov fp
                      ON fp.idfactura= lf.idfactura
                      AND lf.referencia='".$ref."'
                      ORDER BY fp.fecha DESC LIMIT 6";
//      $consulta = "SELECT * FROM historico_costo WHERE referencia = '".$ref."' ORDER BY fecha_compra desc limit 4;";
//      echo " Query".$consulta;
      $historicos = $this->db->select($consulta);
      return $historicos;
   }
   public function url()
   {
      if( !isset($this->cotizacion) )
      {
         return parent::url();
      }
      else if($this->cotizacion)
      {
         return $this->cotizacion->url();
      }
      else
         return $this->page->url();
   }
   
   private function modificar()
   {
      $error = FALSE;
      $this->cotizacion->numproveedor = $_POST['numproveedor'];
      $this->cotizacion->observaciones = $_POST['observaciones'];
      $this->cotizacion->nombre = $_POST['nombre'];
      $this->cotizacion->cifnif = $_POST['cifnif'];
      
      if($this->cotizacion->ptefactura)
      {
         $eje0 = $this->ejercicio->get_by_fecha($_POST['fecha']);
         if($eje0->codejercicio == $this->cotizacion->codejercicio)
         {
            $this->cotizacion->fecha = $_POST['fecha'];
            $this->cotizacion->hora = $_POST['hora'];
         }
         else
         {
            $error = TRUE;
            $this->new_error_msg('La fecha seleccionada está fuere del rando del ejercicio '.$this->cotizacion->codejercicio
                    .'. Si deseas asignar la fecha '.$_POST['fecha'].' pulsa <a href="'.$this->url().'&forze_fecha='.$_POST['fecha'].'">aquí</a>'
                    . ' y se asignará un nuevo código y un nuevo número a la Cotizacion .');
         }
         
         /// ¿Cambiamos el proveedor?
         if($_POST['proveedor'] != $this->cotizacion->codproveedor)
         {
            $proveedor = $this->proveedor->get($_POST['proveedor']);
            if($proveedor)
            {
               $this->cotizacion->codproveedor = $proveedor->codproveedor;
               $this->cotizacion->nombre = $proveedor->razonsocial;
               $this->cotizacion->cifnif = $proveedor->cifnif;
            }
            else
               die('No se ha encontrado el proveedor');
         }
         else
            $proveedor = $this->proveedor->get($this->cotizacion->codproveedor);
         
         $serie = $this->serie->get($this->cotizacion->codserie);
         
         /// ¿cambiamos la serie?
         if($_POST['serie'] != $this->cotizacion->codserie)
         {
            $serie2 = $this->serie->get($_POST['serie']);
            if($serie2)
            {
               $this->cotizacion->codserie = $serie2->codserie;
               $this->cotizacion->irpf = $serie2->irpf;
               $this->cotizacion->new_codigo();
               
               $serie = $serie2;
            }
         }
         
         $this->cotizacion->codpago = $_POST['forma_pago'];
         
         /// ¿Cambiamos la divisa?
         if($_POST['divisa'] != $this->cotizacion->coddivisa)
         {
            $divisa = $this->divisa->get($_POST['divisa']);
            if($divisa)
            {
               $this->cotizacion->coddivisa = $divisa->coddivisa;
               $this->cotizacion->tasaconv = $divisa->tasaconv_compra;
            }
         }
         else if($_POST['tasaconv'] != '')
         {
            $this->cotizacion->tasaconv = floatval($_POST['tasaconv']);
         }
         
         if( isset($_POST['numlineas']) )
         {
            $numlineas = intval($_POST['numlineas']);
            
            $this->cotizacion->neto = 0;
            $this->cotizacion->totaliva = 0;
            $this->cotizacion->totalirpf = 0;
            $this->cotizacion->totalrecargo = 0;
            $lineas = $this->cotizacion->get_lineas();
            $articulo = new articulo();
            
            /// eliminamos las líneas que no encontremos en el $_POST
            foreach($lineas as $l)
            {
               $encontrada = FALSE;
               for($num = 0; $num <= $numlineas; $num++)
               {
                  if( isset($_POST['idlinea_'.$num]) )
                  {
                     if($l->idlinea == intval($_POST['idlinea_'.$num]))
                     {
                        $encontrada = TRUE;
                        break;
                     }
                  }
               }
               if(!$encontrada)
               {
                  if( $l->delete() )
                  {
                     /// actualizamos el stock
                     $art0 = $articulo->get($l->referencia);
                     if($art0)
                        $art0->sum_stock($this->cotizacion->codalmacen, 0 - $l->cantidad);
                  }
                  else
                     $this->new_error_msg("¡Imposible eliminar la línea del artículo ".$l->referencia."!");
               }
            }
            
            /// modificamos y/o añadimos las demás líneas
            for($num = 0; $num <= $numlineas; $num++)
            {
               $encontrada = FALSE;
               if( isset($_POST['idlinea_'.$num]) )
               {
                  foreach($lineas as $k => $value)
                  {
                     /// modificamos la línea
                     if($value->idlinea == intval($_POST['idlinea_'.$num]))
                     {
                        $encontrada = TRUE;
                        $cantidad_old = $value->cantidad;
                        $lineas[$k]->cantidad = floatval($_POST['cantidad_'.$num]);
                        $lineas[$k]->pvpunitario = floatval($_POST['pvp_'.$num]);
                        if(isset($_POST['dto_'.$num]))
                           $lineas[$k]->dtopor = floatval($_POST['dto_'.$num]);
                        $lineas[$k]->pvpsindto = ($value->cantidad * $value->pvpunitario);
                        $lineas[$k]->pvptotal = ($value->cantidad * $value->pvpunitario * (100 - $value->dtopor)/100);
                        $lineas[$k]->descripcion = $_POST['desc_'.$num];
                        
                        $lineas[$k]->codimpuesto = NULL;
                        $lineas[$k]->iva = 0;
                        $lineas[$k]->recargo = 0;
                        $lineas[$k]->irpf = floatval(0);
                        if( !$serie->siniva AND $proveedor->regimeniva != 'Exento' )
                        {
                           $imp0 = $this->impuesto->get_by_iva($_POST['iva_'.$num]);
                           if($imp0)
                              $lineas[$k]->codimpuesto = $imp0->codimpuesto;
                           
                           $lineas[$k]->iva = floatval($_POST['iva_'.$num]);
                           $lineas[$k]->recargo = floatval(0);
                        }
                        
                        if( $lineas[$k]->save() )
                        {
                           $this->cotizacion->neto += $value->pvptotal;
                           $this->cotizacion->totaliva += $value->pvptotal * $value->iva/(100+$value->iva);
                           $this->cotizacion->totalirpf += $value->pvptotal * $value->irpf/100;
                           $this->cotizacion->totalrecargo += $value->pvptotal * $value->recargo/100;
                           
                           if($lineas[$k]->cantidad != $cantidad_old)
                           {
                              /// actualizamos el stock
                              $art0 = $articulo->get($value->referencia);
                              if($art0)
                                 $art0->sum_stock($this->cotizacion->codalmacen, $lineas[$k]->cantidad - $cantidad_old);
                           }
                        }
                        else
                           $this->new_error_msg("¡Imposible modificar la línea del artículo ".$value->referencia."!");
                        break;
                     }
                  }
                  
                  /// añadimos la línea
                  if(!$encontrada AND intval($_POST['idlinea_'.$num]) == -1 AND isset($_POST['referencia_'.$num]))
                  {
                     $linea = new linea_cotizacion_proveedor();
                     $linea->idcotizacion = $this->cotizacion->idcotizacion;
                     $linea->descripcion = $_POST['desc_'.$num];
                     
                     if( !$serie->siniva AND $proveedor->regimeniva != 'Exento' )
                     {
                        $imp0 = $this->impuesto->get_by_iva($_POST['iva_'.$num]);
                        if($imp0)
                           $linea->codimpuesto = $imp0->codimpuesto;
                        
                        $linea->iva = floatval($_POST['iva_'.$num]);
                        $linea->recargo = floatval(0);
                     }
                     
                     $linea->irpf = floatval(0);
                     $linea->cantidad = floatval($_POST['cantidad_'.$num]);
                     $linea->pvpunitario = floatval($_POST['pvp_'.$num]);
                     $linea->dtopor = floatval($_POST['dto_'.$num]);
                     $linea->pvpsindto = ($linea->cantidad * $linea->pvpunitario);
                     $linea->pvptotal = ($linea->cantidad * $linea->pvpunitario * (100 - $linea->dtopor)/100);
                     
                     $art0 = $articulo->get( $_POST['referencia_'.$num] );
                     if($art0)
                     {
                        $linea->referencia = $art0->referencia;
                     }
                     
                     if( $linea->save() )
                     {
                        if($art0)
                        {
                           /// actualizamos el stock
                           $art0->sum_stock($this->cotizacion->codalmacen, $linea->cantidad);
                        }
                        
                        $this->cotizacion->neto += $linea->pvptotal;
                        $this->cotizacion->totaliva += $linea->pvptotal * $linea->iva/(100 + $linea->iva);
                        $this->cotizacion->totalirpf += $linea->pvptotal * $linea->irpf/100;
                        $this->cotizacion->totalrecargo += $linea->pvptotal * $linea->recargo/100;
                     }
                     else
                        $this->new_error_msg("¡Imposible guardar la línea del artículo ".$linea->referencia."!");
                  }
               }
            }
            
            /// redondeamos
            $this->cotizacion->neto = round($this->cotizacion->neto, FS_NF0);
            $this->cotizacion->totaliva = round($this->cotizacion->totaliva, FS_NF0);
            $this->cotizacion->totalirpf = round($this->cotizacion->totalirpf, FS_NF0);
            $this->cotizacion->totalrecargo = round($this->cotizacion->totalrecargo, FS_NF0);
            $this->cotizacion->total = $this->cotizacion->neto - $this->cotizacion->totalirpf + $this->cotizacion->totalrecargo;
            
//            if( abs(floatval($_POST['atotal']) - $this->cotizacion->total) >= .02 )
//            {
//               $this->new_error_msg("El total difiere entre el controlador y la vista (".$this->cotizacion->total.
//                       " frente a ".$_POST['atotal']."). Debes informar del error.");
//            }
         }
      }
      
      if( $this->cotizacion->save() )
      {
         if(!$error)
         {
            $this->new_message(ucfirst("Cotizacion modificado correctamente."));
         }
         
         $this->new_change(ucfirst("Cotizacion").' Proveedor '.$this->cotizacion->codigo, $this->cotizacion->url());
      }
      else
         $this->new_error_msg("¡Imposible modificar la cotizacion!");
   }
   
   private function forzar_fecha()
   {
      $eje0 = $this->ejercicio->get_by_fecha($_GET['forze_fecha']);
      if($eje0)
      {
         $this->cotizacion->fecha = $_GET['forze_fecha'];
         $this->cotizacion->codejercicio = $eje0->codejercicio;
         $this->cotizacion->new_codigo();
         
         if( $this->cotizacion->save() )
         {
            $this->new_message(ucfirst("Cotizacion")." modificado correctamente.");
         }
         else
            $this->new_error_msg("¡Imposible modificar la Cotizacion!");
      }
      else
         $this->new_error_msg('No se ha posido asignar un ejercicio a esta fecha.');
   }
   
   private function generar_orden_compra()
   {
      $orden_compra = new orden_compra_proveedor();
      $orden_compra->cifnif = $this->cotizacion->cifnif;
      $orden_compra->codalmacen = $this->cotizacion->codalmacen;
      $orden_compra->coddivisa = $this->cotizacion->coddivisa;
      $orden_compra->tasaconv = $this->cotizacion->tasaconv;
      $orden_compra->codejercicio = $this->cotizacion->codejercicio;
      $orden_compra->codpago = $this->cotizacion->codpago;
      $orden_compra->codproveedor = $this->cotizacion->codproveedor;
      $orden_compra->codserie = $this->cotizacion->codserie;
      $orden_compra->irpf = $this->cotizacion->irpf;
      $orden_compra->neto = $this->cotizacion->neto;
      $orden_compra->nombre = $this->cotizacion->nombre;
      $orden_compra->cod_cotizacion= $this->cotizacion->codigo;
      $orden_compra->numproveedor = $this->cotizacion->numproveedor;
      $orden_compra->observaciones = $this->cotizacion->observaciones;
      $orden_compra->total = $this->cotizacion->total;
      $orden_compra->totalirpf = $this->cotizacion->totalirpf;
      $orden_compra->totaliva = $this->cotizacion->totaliva;
      $orden_compra->totalrecargo = $this->cotizacion->totalrecargo;
      $orden_compra->codagente = $this->cotizacion->codagente;
      
      /// comprobamos la forma de pago para saber si hay que marcar la orden_compra como pagada
      $forma0 = new forma_pago();
      $formapago = $forma0->get($orden_compra->codpago);
      if($formapago)
      {
         if($formapago->genrecibos == 'Pagados')
         {
            $orden_compra->pagada = TRUE;
         }
      }
      
      /// asignamos la mejor fecha posible, pero dentro del ejercicio
      $eje0 = $this->ejercicio->get($orden_compra->codejercicio);
      $orden_compra->fecha = $eje0->get_best_fecha($orden_compra->fecha);
      
      $regularizacion = new regularizacion_iva();
      
      if( !$eje0->abierto() )
      {
         $this->new_error_msg("El ejercicio está cerrado.");
      }
      else if( $regularizacion->get_fecha_inside($orden_compra->fecha) )
      {
         $this->new_error_msg("El IVA de ese periodo ya ha sido regularizado. No se pueden añadir más orden_compras en esa fecha.");
      }
      else if( $orden_compra->save() )
      {
         $continuar = TRUE;
         foreach($this->cotizacion->get_lineas() as $l)
         {
            $linea = new linea_orden_compra_proveedor();
            $linea->cantidad = $l->cantidad;
            $linea->codimpuesto = $l->codimpuesto;
            $linea->descripcion = $l->descripcion;
            $linea->dtopor = $l->dtopor;
            $linea->idcotizacion = $l->idcotizacion;
            $linea->idpedido = $orden_compra->idpedido;
            $linea->irpf = $l->irpf;
            $linea->iva = $l->iva;
            $linea->pvpsindto = $l->pvpsindto;
            $linea->pvptotal = $l->pvptotal;
            $linea->pvpunitario = $l->pvpunitario;
            $linea->recargo = $l->recargo;
            $linea->referencia = $l->referencia;
            if( !$linea->save() )
            {
               $continuar = FALSE;
               $this->new_error_msg("¡Imposible guardar la línea el artículo ".$linea->referencia."! ");
               break;
            }
         }
         
         if( $continuar )
         {
            $this->cotizacion->idorden_compra = $orden_compra->idpedido;
            $this->cotizacion->ptefactura = FALSE;
            if( $this->cotizacion->save() )
            {
//               $this->generar_asiento($orden_compra);
            }
            else
            {
               $this->new_error_msg("¡Imposible vincular la cotizacion con la nueva orden de compra!");
               if( $orden_compra->delete() )
               {
                  $this->new_error_msg("La orden_compra se ha borrado.");
               }
               else
                  $this->new_error_msg("¡Imposible borrar la orden_compra!");
            }
         }
         else
         {
            if( $orden_compra->delete() )
            {
               $this->new_error_msg("La orden_compra se ha borrado.");
            }
            else
               $this->new_error_msg("¡Imposible borrar la orden_compra!");
         }
      }
      else
         $this->new_error_msg("¡Imposible guardar la orden_compra!");
   }
   
   private function generar_asiento(&$orden_compra)
   {
      if($this->empresa->contintegrada)
      {
         $asiento_orden_compra = new asiento_orden_compra();
         if( $asiento_orden_compra->generar_asiento_compra($orden_compra) )
         {
            $this->new_message("<a href='".$orden_compra->url()."'>orden_compra</a> generada correctamente.");
         }
         
         foreach($asiento_orden_compra->errors as $err)
         {
            $this->new_error_msg($err);
         }
         
         foreach($asiento_orden_compra->messages as $msg)
         {
            $this->new_message($msg);
         }
      }
      else
      {
         $this->new_message("<a href='".$orden_compra->url()."'>orden_compra</a> generada correctamente.");
      }
      
      $this->new_change('orden_compra Proveedor '.$orden_compra->codigo, $orden_compra->url(), TRUE);
   }
}
