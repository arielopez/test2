<?php if(!class_exists('raintpl')){exit;}?><?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("header") . ( substr("header",-1,1) != "/" ? "/" : "" ) . basename("header") );?>
<div class="container-fluid" style="margin-top: 10px; margin-bottom: 10px; margin-left: 10px;">
    <div class="row">
        <div class="col-xs-8">
            <div class="btn-group hidden-xs">
                <a class="btn btn-sm btn-default" href="<?php echo $fsc->url();?>" title="Recargar la página">
                    <span class="glyphicon glyphicon-refresh"></span>
                </a>
                <?php if( $fsc->page->is_default() ){ ?>
                <a class="btn btn-sm btn-default active" href="<?php echo $fsc->url();?>&amp;default_page=FALSE"
                   title="desmarcar como página de inicio">
                    <span class="glyphicon glyphicon-home"></span>
                </a>
                <?php }else{ ?>
                <a class="btn btn-sm btn-default" href="<?php echo $fsc->url();?>&amp;default_page=TRUE"
                   title="marcar como página de inicio">
                    <span class="glyphicon glyphicon-home"></span>
                </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="container container-fluid">
    <div class="row">
        <?php $loop_var1=$fsc->folders(); $counter1=-1; if($loop_var1) foreach( $loop_var1 as $key1 => $value1 ){ $counter1++; ?>
        <div class="col-md-2">
            <b class="text-capitalize"><?php echo $value1;?></b>
            <ul class="list-unstyled">
                <?php $loop_var2=$fsc->pages($value1); $counter2=-1; if($loop_var2) foreach( $loop_var2 as $key2 => $value2 ){ $counter2++; ?>
                <li><a href="<?php echo $value2->url();?>" class="btn btn-sm btn-default"><?php echo $value2->title;?></a></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
    </div>
</div>
<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("footer") . ( substr("footer",-1,1) != "/" ? "/" : "" ) . basename("footer") );?>
