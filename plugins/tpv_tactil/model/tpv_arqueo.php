<?php

/**
 * @author Carlos García Gómez      neorazorx@gmail.com
 * @copyright 2015-2017, Carlos García Gómez. All Rights Reserved. 
 * @copyright 2015-2017, Jorge Casal Lopez. All Rights Reserved.
 */

require_model('agente.php');
require_model('familia.php');
require_model('recibo_cliente.php');

/**
 * Description of tpv_arqueo
 *
 * @author carlos
 */
class tpv_arqueo extends fs_model
{
   public $abierta;
   public $b10;
   public $b100;
   public $b20;
   public $b200;
   public $b5;
   public $b50;
   public $b500;
   public $diadesde;
   public $diahasta;
   public $idasiento;
   public $idtpv_arqueo;
   public $inicio;
   public $m001;
   public $m002;
   public $m005;
   public $m010;
   public $m020;
   public $m050;
   public $m1;
   public $m2;
   public $nogenerarasiento;
   public $ptoventa;
   public $sacadodecaja;
   public $totalcaja;
   public $totalmov;
   public $totaltarjeta;
   public $totalvale;
   public $idterminal;
   public $codagente;
   
   public $agente;
   private static $agentes;
   
   public function __construct($t = FALSE)
   {
      parent::__construct('tpv_arqueos', 'plugins/tpv_tactil/');
      
      if( !isset(self::$agentes) )
      {
         self::$agentes = array();
      }
      
      if($t)
      {
         $this->abierta = $this->str2bool($t['abierta']);
         $this->b10 = intval($t['b10']);
         $this->b100 = intval($t['b100']);
         $this->b20 = intval($t['b20']);
         $this->b200 = intval($t['b200']);
         $this->b5 = intval($t['b5']);
         $this->b50 = intval($t['b50']);
         $this->b500 = intval($t['b500']);
         $this->diadesde = date('d-m-Y', strtotime($t['diadesde']));
         
         $this->diahasta = NULL;
         if( !is_null($t['diahasta']) )
         {
            $this->diahasta = date('d-m-Y', strtotime($t['diahasta']));
         }
         
         $this->idasiento = $this->intval($t['idasiento']);
         $this->idtpv_arqueo = $t['idtpv_arqueo'];
         $this->inicio = floatval($t['inicio']);
         $this->m001 = intval($t['m001']);
         $this->m002 = intval($t['m002']);
         $this->m005 = intval($t['m005']);
         $this->m010 = intval($t['m010']);
         $this->m020 = intval($t['m020']);
         $this->m050 = intval($t['m050']);
         $this->m1 = intval($t['m1']);
         $this->m2 = intval($t['m2']);
         $this->nogenerarasiento = $this->str2bool($t['nogenerarasiento']);
         $this->ptoventa = $t['ptoventa'];
         $this->sacadodecaja = floatval($t['sacadodecaja']);
         $this->totalcaja = floatval($t['totalcaja']);
         $this->totalmov = floatval($t['totalmov']);
         $this->totaltarjeta = floatval($t['totaltarjeta']);
         $this->totalvale = floatval($t['totalvale']);
         
         $this->idterminal = $this->intval($t['idterminal']);
         
         $this->codagente = NULL;
         if( !is_null($t['codagente']) )
         {
            $this->codagente = $t['codagente'];
            foreach(self::$agentes as $ag)
            {
               if( $ag->codagente == $this->codagente )
               {
                  $this->agente = $ag;
                  break;
               }
            }
            
            if( !isset($this->agente) )
            {
               $ag = new agente();
               $this->agente = $ag->get($this->codagente);
               self::$agentes[] = $this->agente;
            }
         }
      }
      else
      {
         $this->abierta = TRUE;
         $this->b10 = 0;
         $this->b100 = 0;
         $this->b20 = 0;
         $this->b200 = 0;
         $this->b5 = 0;
         $this->b50 = 0;
         $this->b500 = 0;
         $this->diadesde = date('d-m-Y');
         $this->diahasta = NULL;
         $this->idasiento = NULL;
         $this->idtpv_arqueo = NULL;
         $this->inicio = 0;
         $this->m001 = 0;
         $this->m002 = 0;
         $this->m005 = 0;
         $this->m010 = 0;
         $this->m020 = 0;
         $this->m050 = 0;
         $this->m1 = 0;
         $this->m2 = 0;
         $this->nogenerarasiento = FALSE;
         $this->ptoventa = NULL;
         $this->sacadodecaja = 0;
         $this->totalcaja = 0;
         $this->totalmov = 0;
         $this->totaltarjeta = 0;
         $this->totalvale = 0;
         
         $this->idterminal = NULL;
         $this->codagente = NULL;
         $this->agente = NULL;
      }
   }
   
   protected function install()
   {
      return '';
   }
   
   public function url()
   {
      if( is_null($this->idtpv_arqueo) )
      {
         return 'index.php?page=tpv_caja';
      }
      else
      {
         return 'index.php?page=tpv_caja&arqueo='.$this->idtpv_arqueo;
      }
   }
   
   public function total_contado()
   {
      $total = $this->m001*0.01;
      $total += $this->m002*0.02;
      $total += $this->m005*0.05;
      $total += $this->m010*0.10;
      $total += $this->m020*0.20;
      $total += $this->m050*0.50;
      $total += $this->m1;
      $total += $this->m2*2;
      $total += $this->b5*5;
      $total += $this->b10*10;
      $total += $this->b20*20;
      $total += $this->b50*50;
      $total += $this->b100*100;
      $total += $this->b200*200;
      $total += $this->b500*500;
      
      return $total;
   }
   
   public function num_tickets()
   {
      $sql = "SELECT COUNT(*) as num FROM tpv_comandas WHERE idfactura AND idtpv_arqueo = ".$this->var2str($this->idtpv_arqueo);
      $data = $this->db->select($sql);
      if($data)
      {
         return intval($data[0]['num']);
      }
      else
      {
         return 0;
      }
   }
   
   public function num_articulos()
   {
      $sql = "SELECT DISTINCT referencia FROM tpv_lineascomanda WHERE idtpv_comanda IN "
              . "(SELECT idtpv_comanda FROM tpv_comandas WHERE idfactura AND idtpv_arqueo = "
              . $this->var2str($this->idtpv_arqueo).');';
      
      $data = $this->db->select($sql);
      if($data)
      {
         return count($data);
      }
      else
      {
         return 0;
      }
   }
   
   public function count_articulos()
   {
      $sql = "SELECT SUM(cantidad) as cantidad FROM tpv_lineascomanda WHERE idtpv_comanda IN "
              . "(SELECT idtpv_comanda FROM tpv_comandas WHERE idfactura AND idtpv_arqueo = "
              . $this->var2str($this->idtpv_arqueo).');';
      
      $data = $this->db->select($sql);
      if($data)
      {
         return floatval($data[0]['cantidad']);
      }
      else
      {
         return 0;
      }
   }
   
   public function desglose_formas_pago()
   {
      $lista = array();
      
      $sql = "SELECT codpago,SUM(totalpago) as total FROM tpv_comandas WHERE idfactura IS NOT NULL AND idtpv_arqueo = "
              .$this->var2str($this->idtpv_arqueo)." GROUP BY codpago ORDER BY total DESC;";
      
      $data = $this->db->select($sql);
      if($data)
      {
         foreach($data as $d)
         {
            $lista[$d['codpago']] = floatval($d['total']);
         }
      }
      
      $sql = "SELECT codpago2,SUM(totalpago2) as total FROM tpv_comandas WHERE idfactura IS NOT NULL AND idtpv_arqueo = "
              .$this->var2str($this->idtpv_arqueo)." GROUP BY codpago2 ORDER BY total DESC;";
      
      $data = $this->db->select($sql);
      if($data)
      {
         foreach($data as $d)
         {
            if( is_null($d['codpago2']) )
            {
               /// nada
            }
            else if( isset($lista[$d['codpago2']]) )
            {
               $lista[$d['codpago2']] += floatval($d['total']);
            }
            else
            {
               $lista[$d['codpago2']] = floatval($d['total']);
            }
         }
      }
      
      return $lista;
   }
   
   public function desglose_familias()
   {
      $lista = array();
      $sql = "SELECT a.codfamilia,l.referencia,l.cantidad FROM tpv_lineascomanda l, articulos a WHERE l.referencia = a.referencia"
              . " AND idtpv_comanda IN (SELECT idtpv_comanda FROM tpv_comandas"
              . " WHERE idfactura AND idtpv_arqueo = ".$this->var2str($this->idtpv_arqueo).');';
      
      $data = $this->db->select($sql);
      if($data)
      {
         $familia = new familia();
         
         foreach($data as $d)
         {
            if( is_null($d['codfamilia']) )
            {
               /// nada
            }
            else if( !isset($lista[$d['codfamilia']]) )
            {
               $lista[$d['codfamilia']] = array(
                   'nombre' => '-',
                   'cantidad' => floatval($d['cantidad'])
               );
               
               $fam = $familia->get($d['codfamilia']);
               if($fam)
               {
                  $lista[$d['codfamilia']]['nombre'] = $fam->descripcion;
               }
            }
            else
            {
               $lista[$d['codfamilia']]['cantidad'] += floatval($d['cantidad']);
            }
         }
      }
      
      return $lista;
   }
   
   public function get($id)
   {
      $data = $this->db->select("SELECT * FROM tpv_arqueos WHERE idtpv_arqueo = ".$this->var2str($id).";");
      if($data)
      {
         return new tpv_arqueo($data[0]);
      }
      else
         return FALSE;
   }
   
   public function exists()
   {
      if( is_null($this->idtpv_arqueo) )
      {
         return FALSE;
      }
      else
         return $this->db->select("SELECT * FROM tpv_arqueos WHERE idtpv_arqueo = ".$this->var2str($this->idtpv_arqueo).";");
   }
   
   public function save()
   {
      if( $this->exists() )
      {
         $sql = "UPDATE tpv_arqueos SET abierta = ".$this->var2str($this->abierta).
                 ", b10 = ".$this->var2str($this->b10).
                 ", b100 = ".$this->var2str($this->b100).
                 ", b20 = ".$this->var2str($this->b20).
                 ", b200 = ".$this->var2str($this->b200).
                 ", b5 = ".$this->var2str($this->b5).
                 ", b50 = ".$this->var2str($this->b50).
                 ", b500 = ".$this->var2str($this->b500).
                 ", codagente = ".$this->var2str($this->codagente).
                 ", diadesde = ".$this->var2str($this->diadesde).
                 ", diahasta = ".$this->var2str($this->diahasta).
                 ", idasiento = ".$this->var2str($this->idasiento).
                 ", idterminal = ".$this->var2str($this->idterminal).
                 ", inicio = ".$this->var2str($this->inicio).
                 ", m001 = ".$this->var2str($this->m001).
                 ", m002 = ".$this->var2str($this->m002).
                 ", m005 = ".$this->var2str($this->m005).
                 ", m010 = ".$this->var2str($this->m010).
                 ", m020 = ".$this->var2str($this->m020).
                 ", m050 = ".$this->var2str($this->m050).
                 ", m1 = ".$this->var2str($this->m1).
                 ", m2 = ".$this->var2str($this->m2).
                 ", nogenerarasiento = ".$this->var2str($this->nogenerarasiento).
                 ", ptoventa = ".$this->var2str($this->ptoventa).
                 ", sacadodecaja = ".$this->var2str($this->sacadodecaja).
                 ", totalcaja = ".$this->var2str($this->totalcaja).
                 ", totalmov = ".$this->var2str($this->totalmov).
                 ", totaltarjeta = ".$this->var2str($this->totaltarjeta).
                 ", totalvale = ".$this->var2str($this->totalvale).
                 "  WHERE idtpv_arqueo = ".$this->var2str($this->idtpv_arqueo).";";
         
         return $this->db->exec($sql);
      }
      else
      {
         $this->idtpv_arqueo = '00000001';
         $sql = "SELECT MAX(".$this->db->sql_to_int('idtpv_arqueo').") as id FROM ".$this->table_name.";";
         $data = $this->db->select($sql);
         if($data)
         {
            $this->idtpv_arqueo = sprintf('%08s', 1 + intval($data[0]['id']));
         }
         
         $sql = "INSERT INTO tpv_arqueos (abierta,codagente,diadesde,diahasta,idasiento,idterminal,inicio,totalcaja,"
                 . "nogenerarasiento,b10,b100,b20,b200,b5,b50,b500,m001,m002,m005,m010,m020,m050,m1,m2,idtpv_arqueo)"
                 . " VALUES (".$this->var2str($this->abierta).
                 ",".$this->var2str($this->codagente).
                 ",".$this->var2str($this->diadesde).
                 ",".$this->var2str($this->diahasta).
                 ",".$this->var2str($this->idasiento).
                 ",".$this->var2str($this->idterminal).
                 ",".$this->var2str($this->inicio).
                 ",".$this->var2str($this->totalcaja).
                 ",".$this->var2str($this->nogenerarasiento).
                 ",".$this->var2str($this->b10).
                 ",".$this->var2str($this->b100).
                 ",".$this->var2str($this->b20).
                 ",".$this->var2str($this->b200).
                 ",".$this->var2str($this->b5).
                 ",".$this->var2str($this->b50).
                 ",".$this->var2str($this->b500).
                 ",".$this->var2str($this->m001).
                 ",".$this->var2str($this->m002).
                 ",".$this->var2str($this->m005).
                 ",".$this->var2str($this->m010).
                 ",".$this->var2str($this->m020).
                 ",".$this->var2str($this->m050).
                 ",".$this->var2str($this->m1).
                 ",".$this->var2str($this->m2).
                 ",".$this->var2str($this->idtpv_arqueo).");";
         
         return $this->db->exec($sql);
      }
   }
   
   public function delete()
   {
      return $this->db->exec("DELETE FROM tpv_arqueos WHERE idtpv_arqueo = ".$this->var2str($this->idtpv_arqueo).";");
   }
   
   public function all($offset = 0)
   {
      $alist = array();
      
      $data = $this->db->select_limit("SELECT * FROM tpv_arqueos ORDER BY idtpv_arqueo DESC", FS_ITEM_LIMIT, $offset);
      if($data)
      {
         foreach($data as $d)
            $alist[] = new tpv_arqueo($d);
      }
      
      return $alist;
   }
   
   public function total_arqueos()
   {
      $total = 0;
      
      $data = $this->db->select("SELECT COUNT(idtpv_arqueo) as total FROM tpv_arqueos;");
      if($data)
      {
         $total = intval($data[0]['total']);
      }
      
      return $total;
   }
   
   public function all_by_agente($codagente, $offset=0, $limit=FS_ITEM_LIMIT)
   {
      $alist = array();
      
      $data = $this->db->select_limit("SELECT * FROM ".$this->table_name." WHERE codagente = ".
              $this->var2str($codagente)." ORDER BY idtpv_arqueo DESC", $limit, $offset);
      if($data)
      {
         foreach($data as $d)
         {
            $alist[] = new tpv_arqueo($d);
         }
      }
      
      return $alist;
   }
}
