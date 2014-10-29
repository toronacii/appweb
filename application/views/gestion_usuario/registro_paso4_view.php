<?php $ultima_planilla_pagada = $this->session->userdata('ultima_planilla_pagada')?>
<?php $ultimo_numero_declaracion = $this->session->userdata('ultimo_numero_declaracion')?>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills nav-justified thumbnail">
            <li class="disabled"><a href="#">
                <h4 class="list-group-item-heading">Paso 1</h4>
                <p class="list-group-item-text">Registro de usuario</p>
            </a></li>
            <li class="disabled"><a href="#">
                <h4 class="list-group-item-heading">Paso 2</h4>
                <p class="list-group-item-text">Registro de cuenta</p>
            </a></li>
            <li class="volver"><a href="#">
                <h4 class="list-group-item-heading">Paso 3</h4>
                <p class="list-group-item-text">Verificación de cuentas</p>
            </a></li>
            <li class="active"><a href="#">
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
    <div class="col-md-3 col-lg-6">
        <h4>Instrucciones</h4>
        <ol>
            <li>Estas son una preguntas de seguridad, referente a cualquiera de sus impuestos de <strong>Actividades Económicas</strong></li>
            <li>Debe Indicar el número de la <a href="#" class="label label-info">última planilla de pago</a> de impuestos cancelada.</li>
            <li>Debe Indicar el número de la <a href="#" class="label label-info">última planilla de declaración</a> de impuestos cancelada.</li>
            <li>Si no puede obtener los numeros solicitados, comunícate al <strong>0800-MISUCRE</strong> o envía un email a <a href="mailto:#">registro.rentas@alcaldiasucre.net</a></li>
        </ol>
    </div>
    <div class="col-md-9 col-lg-6">
        <?php echo form_open(site_url('gestion_usuario/registro/4'), array('class' => 'form-horizontal', "role"=>"form", 'id' => 'registro_usuario'));?>

            <?php if (! $ultima_planilla_pagada || ! $ultimo_numero_declaracion): ?>

                <div class="jumbotron">
                    <h1>¡Ha ocurrido un error!</h1>
                    <p>Debe haber cancelado alguna planilla, o tener alguna declaración liquidada. Comuníquese con nosotros por mas información</p>
                    <p><a href="<?php echo site_url(); ?>" class="btn btn-primary btn-lg" role="button">Volver al inicio</a></p>
                </div>

            <?php else: ?>

            <div class="well well-lg">

                <div class="form-group">

                    <!-- NUMERO DE PLANILLA DE PAGO--> <?php #var_dump($ultima_planilla_pagada, $ultimo_numero_declaracion) ?>                
                    
                    <div class="col-md-6 <?php if (form_error('ult_planilla')) echo "has-error" ?>">
                        <label>Planilla de pago de <strong><?php echo $ultima_planilla_pagada->tax_account_number?></strong></label>
                        <input type="text" class="form-control" placeholder="Ultima planilla de pago" required="required" name="ult_planilla" id="ult_planilla" value="<?php echo set_value('ult_planilla');?>">
                        <span><?php echo form_error('ult_planilla') ?></span>
                    </div>

                    <!-- NUMERO DE DECLARACIÓN-->

                    <div class="col-md-6 <?php if (form_error('ult_declaracion')) echo "has-error" ?>">
                        <label>Planilla de declaración de <strong><?php echo $ultimo_numero_declaracion->tax_account_number?></strong></label>
                        <input type="text" class="form-control" placeholder="Ultima planilla de declaración" required="required" name="ult_declaracion" id="ult_declaracion" value="<?php echo set_value('ult_declaracion');?>">
                        <span><?php echo form_error('ult_declaracion') ?></span>
                    </div>


                </div>  

                <div class="form-group">

                    <div class="col-md-6 col-md-offset-6">
                        <div class="pull-right">
                            <input id="button-volver" type="submit" class="btn btn-primary" name="volver" value="Volver">
                            <input id="button-submit" type="submit" class="btn btn-success" name="preguntas_validacion" value="Continuar">
                        </div>
                    </div>
                </div>  

            </div>

            <?php endif; ?> 

        <?php echo form_close() ?>
    </div>
</div>