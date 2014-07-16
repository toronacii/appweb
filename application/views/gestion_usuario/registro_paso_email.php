
<div class="row">
	<div class="col-md-12 center">
		<div class="jumbotron">
			<?php if ($valid_email): ?>
			<h1>!Gracias por activar su usuario!</h1>
			<p>
				Por favor pulse el enlace para iniciar sesion y ver la posicion consolidada de sus impuestos, opciones de tramites, pagos que puede realizar ¡y mucho mas!<br>
				<p><a href='http://www.alcaldiamunicipiosucre.gov.ve/contenido/alcaldia/organigrama/direccion-de-rentas/' class="btn btn-primary btn-lg" role="button">Iniciar sesión</a></p>
			</p>
			<?php else: ?>
			<h1>¡No se ha podido activar tu usuario!</h1>
			<p>Comunícate con nosotros para solventarlo o inténtalo mas tarde</p>
		    <?php endif;?>
		</div>
	</div>
</div>
