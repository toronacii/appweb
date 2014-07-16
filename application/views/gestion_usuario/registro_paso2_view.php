<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills nav-justified thumbnail">
            <li class="volver"><a href="#">
                <h4 class="list-group-item-heading">Paso 1</h4>
                <p class="list-group-item-text">Registro de usuario</p>
            </a></li>
            <li class="active"><a href="#">
                <h4 class="list-group-item-heading">Paso 2</h4>
                <p class="list-group-item-text">Registro de cuenta</p>
            </a></li>
            <li class="disabled"><a href="#">
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
	<div class="col-md-3 col-lg-6">
		<h4>Instrucciones</h4>
		<ol>
			<li>Coloque el número de cuenta de Actividades Económicas, Inmuebles o Vehiculos.</li>
			<li>Si no posee un número de cuenta, debe registrarse primero en nuestras oficinas.</li>
			<li>Seleccione el tipo de formato e introduzca el número de cuenta (cuenta nueva ej.<strong>010023456</strong>, cuenta renta ej. <strong>01-2-003-04567-8</strong>).</li>
		</ol>
	</div>
	<div class="col-md-9 col-lg-6">
		<?php echo form_open(site_url('gestion_usuario/registro/2'), array('class' => 'form-horizontal', "role"=>"form", 'id' => 'registro_usuario'));?>

		<div class="well well-lg">

			<div class="form-group">

				<!-- TIPO DE PERSONA -->
				
				<div class="col-md-6">
					<label for="tipo_cuenta">Tipo de cuenta</label>
					<select id="tipo_cuenta" name="tipo_cuenta" class="form-control">
	                	<option value="cuentanueva" <?php echo set_select('tipo_cuenta','cuentanueva') ?>>Cuenta Nueva</option>
	                	<option value="cuentarenta" <?php echo set_select('tipo_cuenta','cuentarenta') ?>>Cuenta Renta</option>
	                </select>
				</div>

				<!-- CEDULA-->

				<div id="natural" class="col-md-6 <?php if (form_error('cedula')) echo "has-error" ?>">
					<label>&nbsp;</label>
					<input type="text" class="form-control cuentaNueva" maxlength="9"  placeholder="Cuenta nueva" required="required" name="cuentanueva" id="cuentanueva" value="<?php echo set_value('cuentanueva');?>">
					<input type="text" class="form-control cuentaRenta hide" maxlength="16" placeholder="Cuenta renta" name="cuentarenta" id="cuentarenta" value="<?php echo set_value('cuentarenta');?>">
					<span class="cuentanueva-error"><?php echo form_error('cuentanueva') ?></span>
					<span class="cuentarenta-error"><?php echo form_error('cuentarenta') ?></span>
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

		</div>

		<?php echo form_close() ?>
	</div>
</div>