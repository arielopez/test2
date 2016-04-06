<?php if(!class_exists('raintpl')){exit;}?><?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("header") . ( substr("header",-1,1) != "/" ? "/" : "" ) . basename("header") );?>

<?php if( $fsc->columnas ){ ?>

<ol class="breadcrumb">
    <li><a href="<?php echo $fsc->url();?>">Tablas</a></li>
    <li class="active"><?php echo $_GET['table'];?></li>
</ol>
<h2>Detalles de la tabla <?php echo $_GET['table'];?></h2>
<table class="table table-hover col-lg-8" style="max-width: 1200px; ">
    <thead>
    <tr>
        <th>
            Nombre
        </th>
        <th>Tipo</th>
        <th>Valor por defecto</th>
        <th>Nulos?</th>
        <th>Extras</th>
    </tr>
    </thead>
    <tbody>
    <?php $loop_var1=$fsc->columnas; $counter1=-1; if($loop_var1) foreach( $loop_var1 as $key1 => $value1 ){ $counter1++; ?>

    <tr>
        <td><?php echo $value1['column_name'];?></td>
        <td><?php echo $value1['data_type'];?></td>
        <td><?php echo $value1['column_default'];?></td>
        <td><?php echo $value1['is_nullable'];?></td>
        <td><?php echo $value1['extra'];?></td>
    </tr>
    <?php } ?>

    </tbody>
</table>
<?php }else{ ?>

<!--<h1>Listado de tablas</h1>-->
<ol class="breadcrumb" >
    <li class="active">Tablas</li>
</ol><div class="container-fluid">
    <div class="row">
        <?php $loop_var1=$fsc->tablas; $counter1=-1; if($loop_var1) foreach( $loop_var1 as $key1 => $value1 ){ $counter1++; ?>

        <div class="col-lg-3">
            <?php echo $counter1+1;?>-<a href="<?php echo $fsc->url();?>&table=<?php echo $value1['name'];?>"><?php echo $value1['name'];?></a></div>
        <?php } ?>

    </div>
</div>
<?php } ?>


<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("footer") . ( substr("footer",-1,1) != "/" ? "/" : "" ) . basename("footer") );?>