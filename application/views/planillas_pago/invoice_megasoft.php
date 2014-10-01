<div class="panel panel-primary" id="imprimir">
	<?php if (isset($titulo)): ?>
	<div class="panel-heading center"><?php echo $titulo ?> </div>
	<ul class="list-group">
		<li class="list-group-item">
			<div class="row">
				<div class="col-md-6"><strong>Nº Planilla</strong></div>
				<div class="col-md-6"><?php echo $factura ?></div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-md-6"><strong>Monto</strong></div>
				<div class="col-md-6"><?php echo number_format($monto, 2, ',', '.') ?></div>
			</div>
		</li>
		<?php if ($tarjeta): ?>
		<li class="list-group-item">
			<div class="row">
				<div class="col-md-6"><strong>Número de tarjeta</strong></div>
				<div class="col-md-6"><?php echo $tarjeta ?></div>
			</div>
		</li>
		<?php endif; ?>
		<?php if ($descripcion): ?>
		<li class="list-group-item">
			<div class="row">
				<div class="col-md-6"><strong>Descripción</strong></div>
				<div class="col-md-6"><?php echo $descripcion ?></div>
			</div>
		</li>
		<?php endif; ?>
		<?php if ($voucher): ?>
		<li class="list-group-item">
			<div class="row">
				<div class="col-md-6"><strong>Voucher</strong></div>
				<div class="col-md-6"><pre style="background-color:white; border:none"><?php echo $voucher ?></pre></div>
			</div>
		</li>
		<?php endif; ?>
		<li class="list-group-item">
			<div class="row text-right">
				<?php if ($estado !== 'P'): ?>
				<a href="<?php echo site_url("generar_planilla/imprime_pago_megasoft/$control") ?>" class="btn btn-primary" target="_blank">Imprimir</a>
				<?php endif; ?>
				<a href="<?php echo $pagina ?>" class="btn btn-default">Volver</a>
			</div>
		</li>
	</ul>
	<?php else: ?>
	<div class="panel-heading center">Error</div>
	<div class="panel-body text-center">Ha ocurrido un error con su factura</div>
	<?php endif; ?>
</div>