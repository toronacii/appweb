<?php $cuentas = (object)$this->session->userdata('cuentas'); ?>
<script type="text/javascript">
    $(function () {
        $('.datatable').dataTable({
            "aaSorting": [[0,'desc']],
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
        });

        $('.dataTables_filter input').addClass('form-control').attr('placeholder','Buscar');
        $('.dataTables_length select').addClass('form-control');

    });
</script>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills nav-justified thumbnail">
            <li class="disabled"><a href="#">
                <h4 class="list-group-item-heading">Paso 1</h4>
                <p class="list-group-item-text">Registro de usuario</p>
            </a></li>
            <li class="volver"><a href="#">
                <h4 class="list-group-item-heading">Paso 2</h4>
                <p class="list-group-item-text">Registro de cuenta</p>
            </a></li>
            <li class="active"><a href="#">
                <h4 class="list-group-item-heading">Paso 3</h4>
                <p class="list-group-item-text">Verificación de cuentas</p>
            </a></li>
            <li class="disabled"><a href="#">
                <h4 class="list-group-item-heading">Paso 4</h4>
                <p class="list-group-item-text">Preguntas de seguridad</p>
            </a></li>
            <li class="disabled"><a href="#">
                <h4 class="list-group-item-heading">Paso 5</h4>
                <p class="list-group-item-text">Finalizar</p>
            </a></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-3 col-lg-4">
        <h4>Instrucciones</h4>
        <ol>
            <li>Si desea agregar una cuenta, puede solicitar que sea agregada (se le podran solicitar datos especificos para la validación).</li>
            <li>Seleccione el tipo de formato e introduzca el número de cuenta (cuenta nueva ej.<strong>010023456</strong>, cuenta renta ej. <strong>01-2-003-04567-8</strong>).</li>
            <li>Si desea eliminar una cuenta de la lista, presione el boton <h5 class="inline"><label class="label label-danger">Quitar</label></h5></li>
        </ol>
        <div class="mensaje alert alert-info hide">
            Las cuentas agregadas serán revisadas para su posterior aprobación
        </div>
    </div>
    <div class="col-md-9 col-lg-8">
        <?php echo form_open(site_url('gestion_usuario/registro/3'), array('class' => 'form-horizontal', "role"=>"form", 'id' => 'registro_usuario'));?>
            <input type="hidden" id="data_enviada" name="data_enviada" value="<?php echo $cuentas->id_taxpayer ?>" />

            <div class="panel panel-success">
            <!-- Default panel contents -->
                <div class="panel-heading center">Cuenta(s) Contribuyente N° <?php echo $cuentas->id_taxpayer ?></div>

                <table class="table center table-striped datatable" id="tableCuentas">
                    <thead>
                    <tr>
                        <th width="10%">N°</th>
                        <th width="40%">Cuenta nueva</th>
                        <th width="40%">Cuenta renta</th>
                        <th width="10%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($cuentas->cuentas as $iObj => $objCuenta): ?> 
                    <tr>
                        <td><?php echo $iObj + 1?></td>
                        <td class='n_cuentarenta'><?php echo $objCuenta->rent_account?></td>
                        <td class='n_cuentanueva'><?php echo $objCuenta->tax_account_number?></td>
                        <td>&nbsp;</td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot></tfoot>
                    
               </table> 

                <div class="panel-body well well-lg">

                    <div class="center hide-if-no-paging">
                        <ul class="pagination" style="margin-top:10px"></ul>
                    </div>

                    <div class="form-group">
                        <div class="alert alert-danger hide error"></div>
                        <div class="col-md-5">
                            <select id="tipo_cuenta" name="tipo_cuenta" class="form-control">
                                <option value="cuentanueva" <?php echo set_select('tipo_cuenta','cuentanueva') ?>>Cuenta Nueva</option>
                                <option value="cuentarenta" <?php echo set_select('tipo_cuenta','cuentarenta') ?>>Cuenta Renta</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control cuentaNueva no-required" maxlength="9"  placeholder="Cuenta nueva" name="cuentanueva" id="cuentanueva" >
                            <input type="text" class="form-control cuentaRenta hide no-required" maxlength="16" placeholder="Cuenta renta" name="cuentarenta" id="cuentarenta">
                        </div>
                        <div class="col-md-2">
                            <a id="agregar" type="submit" class="btn btn-info">Agregar</a>
                        </div>
                    </div>
                    
                </div>

            </div> 

            <div class="form-group">

                <div class="col-md-6 col-md-offset-6">
                    <div class="pull-right">
                        <input id="button-volver" type="submit" class="btn btn-primary" name="volver" value="Volver">
                        <input id="button-submit" type="submit" class="btn btn-success" name="datos_cuenta" value="Continuar">
                    </div>
                </div>
            </div>  

        <?php echo form_close() ?>
    </div>
</div>