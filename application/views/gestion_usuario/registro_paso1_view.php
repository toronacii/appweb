
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills nav-justified thumbnail">
            <li class="active"><a href="#">
                <h4 class="list-group-item-heading">Paso 1</h4>
                <p class="list-group-item-text">Registro de usuario</p>
            </a></li>
            <li class="disabled"><a href="#">
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
	<div class="col-md-4 col-lg-6">
		<h4>Instrucciones</h4>
		<ol>
			<li>Para tener acceso a la información de tus cuentas, realizar pagos en línea y solicitar trámites debes registrarte en nuestra Oficina Virtual.</li>
			<li>Necesitarás un correo electrónico válido, tener a mano la última Declaración de Ingresos Brutos (Definitiva o Estimada) presentada, y la última planilla de pago compensada en caso de tener cuentas de Actividades Económicas.</li>
			<li>Si vas a registrarte como Persona Jurídica, te recomendamos hacerlo con una cuenta de correo corporativa que permanezca en el tiempo (no personal).</li>
			<li>El correo electrónico será tu usuario. Si te equivocas tienes que escribir a: <a href="#">registro.rentas@alcaldiasucre.net</a></li>
			<li>Si durante el proceso de registro nuestro sistema rechaza tu solicitud escribe a: <a href="#">registro.rentas@alcaldiasucre.net</a></li>
			<li><strong>Todos los campos del formulario son obligatorios. Si falta llenar alguno, el sistema no te permitirá avanzar al siguiente paso, y te mostrará un error. Por favor verifique que ha llenado todos los datos.</strong></li>
		</ol>
	</div>
	<div class="col-md-8 col-lg-6">
		<?php echo form_open(site_url('gestion_usuario/registro'), array('class' => 'form-horizontal', "role"=>"form", 'id' => 'registro_usuario'));?>

		<div class="well well-lg">

			<div class="form-group">

				<!-- TIPO DE PERSONA -->
				
				<div class="col-md-6">
					<label for="tipo_persona">Tipo de persona</label>
					<select name="tipo_persona" id="tipo_persona" class="form-control">
						<option value="natural" <?php echo set_select('tipo_persona','natural') ?>>Natural</option>
						<option value="juridica" <?php echo set_select('tipo_persona','juridica') ?>>Jurídica</option>
					</select>
				</div>

				<!-- CEDULA-->

				<div id="natural" class="col-md-6 <?php if (form_error('cedula')) echo "has-error" ?>">
					<label for="cedula">Cédula</label>
					<input type="text" class="form-control" placeholder="Cédula" required="required" name="cedula" id="cedula" value="<?php echo set_value('cedula');?>">
					<?php echo form_error('cedula') ?>
				</div>

				
			</div>

			<div id="juridica" class="form-group hide">

				<!-- RIF-->

				<div class="col-md-6 <?php if (form_error('rif')) echo "has-error" ?>">
					<label for="rif">Rif</label>
					<input type="text" class="form-control formatoRif" placeholder="Rif" required="required" name="rif" id="rif" value="<?php echo set_value('rif');?>">
					<small class="help-block">Ejemplo: <strong>J-12345678-1</strong>, ingrese la letra J o G al inicio, no debe colocar los guiones solo los numeros</small>
					<?php echo form_error('rif') ?>
				</div>

				<!-- RAZON SOCIAL -->
				
				<div class="col-md-6 <?php if (form_error('razon_social')) echo "has-error" ?>">
					<label for="razon_social">Razón social</label>
					<input type="text" class="form-control formatoMayuscula" placeholder="Razón social" required="required" name="razon_social" id="razon_social" value="<?php echo set_value('razon_social');?>">
					<?php echo form_error('razon_social') ?>
				</div>
				
			</div>

			<div class="form-group">

				<!-- NOMBRES-->
				
				<div class="col-md-6 <?php if (form_error('nombres')) echo "has-error" ?>">
					<label for="nombres">Nombres</label>
					<input type="text" class="form-control textoConAcentos" placeholder="Nombres" required="required" name="nombres" id="nombres" value="<?php echo set_value('nombres');?>">
					<?php echo form_error('nombres') ?>
				</div>

				<!-- APELLIDOS-->

				<div class="col-md-6 <?php if (form_error('apellidos')) echo "has-error" ?>">
					<label for="apellidos">Apellidos</label>
					<input type="text" class="form-control textoConAcentos" placeholder="Apellidos" required="required" name="apellidos" id="apellidos" value="<?php echo set_value('apellidos');?>">
					<?php echo form_error('apellidos') ?>
				</div>

				
			</div>

			<div class="form-group">

				<!-- TELEFONO LOCAL-->
				
				<div class="col-md-6 <?php if (form_error('tlf_local')) echo "has-error" ?>">
					<label for="tlf_local">Teléfono local</label>
					<input type="text" class="form-control formatoTelefonoLocal" placeholder="Teléfono local" required="required" name="tlf_local" id="tlf_local" value="<?php echo set_value('tlf_local');?>">
					<small class="help-block">Ejemplo: <strong>0212-555-55-55</strong>, ingrese el codigo de area al inicio, no debe colocar los guiones solo los numeros</small>
					<?php echo form_error('tlf_local') ?>
				</div>

				<!-- TELEFONO CELULAR-->

				<div class="col-md-6 <?php if (form_error('tlf_celular')) echo "has-error" ?>">
					<label for="tlf_celular">Teléfono celular</label>
					<input type="text" class="form-control formatoTelefono" placeholder="Teléfono celular" required="required" name="tlf_celular" id="tlf_celular" value="<?php echo set_value('tlf_celular');?>">
					<small class="help-block">Ejemplo: <strong>0412-555-55-55</strong>, ingrese el codigo de area al inicio, no debe colocar los guiones solo los numeros</small>
					<?php echo form_error('tlf_celular') ?>
				</div>

				
			</div>

			<div class="form-group">

				<!-- CORREO ELECTRÓNICO-->
				
				<div class="col-md-6 <?php if (form_error('email')) echo "has-error" ?>">
					<label for="email">Correo electrónico</label>
					<input type="email" class="form-control" placeholder="Correo electrónico" required="required" name="email" id="email" value="<?php echo set_value('email');?>">
					<?php echo form_error('email') ?>
				</div>

				<!-- CONFIRMAR CORREO ELECTRÓNICO-->

				<div class="col-md-6 <?php if (form_error('conf_email')) echo "has-error" ?>">
					<label for="conf_email">Confirmar correo electrónico</label>
					<input type="email" class="form-control" placeholder="Confirmar correo electrónico" required="required" name="conf_email" id="conf_email" value="<?php echo set_value('conf_email');?>">
					<?php echo form_error('conf_email') ?>
				</div>

				
			</div>

			<div class="form-group">

				<!-- CONTRASEÑA-->
				
				<div class="col-md-6 <?php if (form_error('pass')) echo "has-error" ?>">
					<label for="pass">Contraseña</label>
					<input type="password" class="form-control" placeholder="Contraseña" required="required" name="pass" id="pass" value="<?php echo set_value('pass');?>">
					<?php echo form_error('pass') ?>
				</div>

				<!-- CONFIRMAR CONTRASEÑA-->

				<div class="col-md-6">
					<label for="conf_pass">Confirmar contraseña</label>
					<input type="password" class="form-control" placeholder="Confirmar contraseña" required="required" name="conf_pass" id="conf_pass" value="<?php echo set_value('conf_pass');?>">
				</div>

				
			</div>

			<div class="form-group">
				<div class="col-md-6">
				
					<div class="checkbox <?php if (form_error('contrato')) echo "has-error" ?>">
						<label>
							<input type="checkbox" name="contrato" value='1' id="contrato" <?php echo set_checkbox('contrato','1')?> required/>
							Acepto el <a target='blank' href="<?=site_url()?>/gestion_usuario/contrato">Contrato de Adhesión</a>
						</label>
					</div>
					<?php echo form_error('contrato') ?>
				</div>

				<div class="col-md-6">
					<input id="button-modal" type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal" value="Validar que eres un humano">
					<!--<h4 id="label-button-modal" class="hide"><span class="label label-success">¡Eres un humano!</span></h4>-->
					<input id="button-submit" type="submit" class="btn btn-success hide pull-right" value="Continuar">

				</div>
			</div>

		</div>

		<?php echo form_close() ?>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Prueba que eres un humano</h4>
    </div>
    <div class="modal-body">

		<div id="myBootstrapCaptchaDiv"></div>

    </div>

</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
