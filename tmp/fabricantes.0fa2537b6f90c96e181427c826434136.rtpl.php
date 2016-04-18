<?php if(!class_exists('raintpl')){exit;}?><?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("header_modificado2") . ( substr("header_modificado2",-1,1) != "/" ? "/" : "" ) . basename("header_modificado2") );?>


<script type="text/javascript">
   $(document).ready(function() {   
      document.f_custom_search.query.focus();
      
      $("#b_nuevo_fabricante").click(function(event) {
         event.preventDefault();
         $("#modal_nuevo_fabricante").modal('show');
         document.f_nuevo_fabricante.ncodfabricante.focus();
      });
   });
</script>

<div class="">
   <div class="row">
      <div class="col-sm-12">
         <div class="page-header">
            <h1>
               <a class="btn btn-xs btn-default" href="index.php?page=articulos">
                  <span class="glyphicon glyphicon-arrow-left"></span>
               </a> &nbsp;
               <?php if( $fsc->query=='' ){ ?>

               <span class="glyphicon glyphicon-folder-open"></span>
               &nbsp; Fabricantes
               <small><?php echo $fsc->total_fabricantes();?></small>
               <?php }else{ ?>

               <span class="glyphicon glyphicon-search"></span>
               &nbsp; Resultados de '<?php echo $fsc->query;?>'
               <?php } ?>

               <a class="btn btn-xs btn-default" href="<?php echo $fsc->url();?>" title="Recargar la página">
                  <span class="glyphicon glyphicon-refresh"></span>
               </a>
            </h1>
            <p class="help-block">
               Introduce aquí todos los fabricantes de los artículos que vendes o compras.
            </p>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-sm-9">
         <div class="btn-group">
            <a id="b_nuevo_fabricante" class="btn btn-sm btn-success" href="#">
               <span class="glyphicon glyphicon-plus"></span>
               <span class="hidden-xs">&nbsp; Nuevo</span>
            </a>
            <?php $loop_var1=$fsc->extensions; $counter1=-1; if($loop_var1) foreach( $loop_var1 as $key1 => $value1 ){ $counter1++; ?>

               <?php if( $value1->type=='button' ){ ?>

               <a href="index.php?page=<?php echo $value1->from;?><?php echo $value1->params;?>" class="btn btn-sm btn-default"><?php echo $value1->text;?></a>
               <?php } ?>

            <?php } ?>

         </div>
      </div>
      <div class="col-sm-3">
         <div class="visible-xs">
            <br/>
         </div>
         <form name="f_custom_search" action="<?php echo $fsc->url();?>" method="post" class="form">
            <div class="input-group">
               <input class="form-control" type="text" name="query" value="<?php echo $fsc->query;?>" autocomplete="off" placeholder="Buscar">
               <span class="input-group-btn">
                  <button class="btn btn-primary hidden-sm" type="submit">
                     <span class="glyphicon glyphicon-search"></span>
                  </button>
               </span>
            </div>
         </form>
      </div>
   </div>
   <div class="row">
      <div class="col-sm-12">
         <br/>
      </div>
   </div>
   <div class="row">
      <?php $loop_var1=$fsc->resultados; $counter1=-1; if($loop_var1) foreach( $loop_var1 as $key1 => $value1 ){ $counter1++; ?>

      <div class="col-sm-3" style="margin-bottom: 5px;">
         <a class="btn btn-block btn-default" href="<?php echo $value1->url();?>">
            <span class="glyphicon glyphicon-folder-open"></span>
            &nbsp; <?php echo $value1->nombre();?>

         </a>
      </div>
      <?php }else{ ?>

      <div class="col-sm-12">
         <div class="alert alert-warning">Ningún fabricante encontrado. Pulsa el botón <b>Nuevo</b> para crear uno.</div>
      </div>
      <?php } ?>

   </div>
</div>

<form class="form" name="f_nuevo_fabricante" action="<?php echo $fsc->url();?>" method="post">
   <div class="modal" id="modal_nuevo_fabricante">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title">Nuevo fabricante</h4>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  Código:
                  <input class="form-control" type="text" name="ncodfabricante" maxlength="8" autocomplete="off"/>
               </div>
               <div class="form-group">
                  Descripción:
                  <input class="form-control" type="text" name="nnombre" autocomplete="off"/>
               </div>
            </div>
            <div class="modal-footer">
               <button class="btn btn-sm btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
                  <span class="glyphicon glyphicon-floppy-disk"></span> &nbsp; Guardar
               </button>
            </div>
         </div>
      </div>
   </div>
</form>
</div>
   </div>

<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("footer2") . ( substr("footer2",-1,1) != "/" ? "/" : "" ) . basename("footer2") );?>