
<?php $registro = $this->session->userdata('registro'); #var_dump($registro)?>

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
            <li class="disabled"><a href="#">
                <h4 class="list-group-item-heading">Paso 3</h4>
                <p class="list-group-item-text">Verificación de cuentas</p>
            </a></li>
            <li class="disabled"><a href="#">
                <h4 class="list-group-item-heading">Paso 4</h4>
                <p class="list-group-item-text">Preguntas de seguridad</p>
            </a></li>
            <li class="active"><a href="#">
                <h4 class="list-group-item-heading">Paso 5</h4>
                <p class="list-group-item-text">Fin del registro</p>
            </a></li>
        </ul>
    </div>
</div>

<div class="row">
	<div class="col-md-12 center">
		<div class="jumbotron">
			<?php if (@$registro['send_email']): ?>
				<h1>¡Bienvenido!</h1>
				<p>Su usuario se ha creado de manera exitosa. <br>
				En su buzón de correo electrónico encontrará un mensaje con las instrucciones para activarlo. <br>
				En caso de no aparecer en su bandeja de entrada recuerde revisar en SPAM o CORREO NO DESEADO <br><br>
				Gracias por registrarse en la Oficina Virtual de la Direcci&oacute;n de Rentas</p>
				<p><a href="<?php echo site_url(); ?>" class="btn btn-primary btn-lg" role="button">Ir a la página de autenticación</a></p>
			<?php else: ?>
				<h1>¡Ha ocurrido un error!</h1>
				<p>Comunícate con nosotros para solventarlo o inténtalo mas tarde</p>
				<p><a href="<?php echo site_url('gestion_usuario/registro'); ?>" class="btn btn-primary btn-lg" role="button">Intentar de nuevo</a></p>
		    <?php endif;?>
		</div>
	</div>
</div>


