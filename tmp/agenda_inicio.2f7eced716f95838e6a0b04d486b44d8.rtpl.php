<?php if(!class_exists('raintpl')){exit;}?><?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("header") . ( substr("header",-1,1) != "/" ? "/" : "" ) . basename("header") );?>

<h1 style="margin-left:20px;padding-top: 10px; "> Agenda</h1>

<div class=" panel">
    <div>
        <br>
        <!-- Nav tabs -->


        <ul class="nav nav-tabs" role="tablist">
            <li><a href="<?php echo $fsc->url();?>">
                <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
            </a></li>
            <li role="presentation" <?php if( !$fsc->editar ){ ?> class="active" <?php } ?> ><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Listado</a>
            </li>
            <li role="presentation"><a href="#nuevo" aria-controls="nuevo" role="tab" data-toggle="tab">Nuevo</a></li>
            <?php if( $fsc->editar ){ ?>

            <li role="presentation" class="active"><a href="#editar" aria-controls="editar" role="tab" data-toggle="tab">Editar</a></li>
            <?php } ?>

        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane <?php if( !$fsc->editar ){ ?> active <?php } ?>" id="home">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th class="col-md-2">Fecha</th>
                            <th>Tarea</th>
                            <th class="col-md-1">Usuario</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $loop_var1=$fsc->listado; $counter1=-1; if($loop_var1) foreach( $loop_var1 as $key1 => $value1 ){ $counter1++; ?>

                        <?php if( $value1["usuario"]==$fsc->user->nick ){ ?>

                        <tr class="bg-success">
                            <?php }else{ ?>

                        <tr>
                            <?php } ?>

                            <td><a href="<?php echo $fsc->url();?>&id=<?php echo $value1['id'];?>"><?php echo $value1['fecha'];?></a></td>
                            <td><?php echo $value1['tarea'];?></td>
                            <td><?php echo $value1['usuario'];?></td>
                        </tr>
                        <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="nuevo">
                <form action="<?php echo $fsc->url();?>" class="form" method="post" style="margin-top: 10px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    Fecha:
                                    <input type="text" value="<?php echo $fsc->today();?>" class="form-control datepicker "
                                           name="fecha">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    Hora:
                                    <input type="text" name="hora" value="<?php echo $fsc->hour();?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    Usuario:
                                    <select name="usuario" id="listUsers" class="form-control">
                                        <option value="">Ninguno</option>
                                        <option value="">-------</option>
                                        <?php $loop_var1=$fsc->user->all(); $counter1=-1; if($loop_var1) foreach( $loop_var1 as $key1 => $value1 ){ $counter1++; ?>

                                        <option value="<?php echo $value1->nick;?>"><?php echo $value1->nick;?></option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea name="tarea" rows="5" class="form-control"></textarea>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"> </span>
                                        Guardar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php if( $fsc->editar ){ ?>

            <div role="tabpanel" class="tab-pane  active " id="editar">
                <form action="<?php echo $fsc->url();?>" class="form" method="post" style="margin-top: 10px;">
                    <input type="hidden" name="id" value="<?php echo $fsc->editar["0"]['id'];?>">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12"><h3>Editando Tarea</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    Fecha:
                                    <input type="text" value="<?php echo $fsc->separa_fecha();?>" class="form-control datepicker "
                                           name="efecha">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    Hora:
                                    <input type="text" name="hora" value="<?php echo $fsc->separa_hora();?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    Usuario:
                                    <select name="usuario" id="listUsers" class="form-control">
                                        <option value="">Ninguno</option>
                                        <option value="">-------</option>
                                        <?php $loop_var1=$fsc->user->all(); $counter1=-1; if($loop_var1) foreach( $loop_var1 as $key1 => $value1 ){ $counter1++; ?>

                                        <?php if( $value1->nick==$fsc->editar["0"]['usuario'] ){ ?>

                                        <option value="<?php echo $value1->nick;?>" selected="selected"><?php echo $value1->nick;?></option>
                                        <?php }else{ ?>

                                        <option value="<?php echo $value1->nick;?>"><?php echo $value1->nick;?></option>
                                        <?php } ?>

                                        <?php } ?>

                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea name="tarea" rows="5" class="form-control"><?php echo $fsc->editar["0"]['tarea'];?></textarea>
                                </div>
                                <div class="text-right">
                                    <a href="<?php echo $fsc->url();?>&delete=<?php echo $fsc->editar["0"]['id'];?>" class="btn btn-sm btn-danger">
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                        Eliminar</a>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"> </span>
                                        Guardar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php } ?>

            </div>
        </div>

    </div>
</div>

<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("footer") . ( substr("footer",-1,1) != "/" ? "/" : "" ) . basename("footer") );?>

