<h3>Confirmación</h3>

<div class="col-md-6">
	<?php $this->load->view('planillas_pago/partials/info_cuenta', array('taxpayer' => $taxpayer, 'tax' => $tax, 'tax_types' => $tax_types)) ?>
</div>

<div class="col-md-6">
	<div class="panel panel-primary">
		<div class="panel-heading center">Verifica tu planilla de pago</div>
		<ul class="list-group">
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-6"><strong>Concepto</strong></div>
					<div class="col-md-6"><?php echo $tasa->name ?></div>
				</div>
			</li>
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-6"><strong>Unidades Tributarias</strong></div>
					<div class="col-md-6"><?php echo number_format($tasa->tax_unit, 2, ',','.') ?></div>
				</div>
			</li>
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-6"><strong>Monto de la planilla</strong></div>
					<div class="col-md-6"><?php echo number_format($tasa->value * $tasa->tax_unit, 2, ',','.') ?></div>
				</div>
			</li>
		</ul>
		<form action="<?php echo site_url('planillas_pago/generar_planilla_tasa') ?>" method="POST"  id="div_tasas" target="_blank">
			<input type="hidden" name="action" id="action" value="">
			<div class="panel-footer center">
				<fieldset>
					<?php $page = ($this->session->userdata('usuario_appweb')) ? 'planillas_pago/tasas' : 'eventual/express' ?>
					<a href="<?php site_url($page) ?>" class="btn btn-default" title="Selecciona otra tasa">Volver</a>
					<input  class="btn btn-success" title="Imprime la planilla si vas a pagar por el banco" type="submit" value="Imprimir planilla" name="imprimir">

					<?php if ($this->session->userdata('usuario_appweb')): ?>
					<input  class="btn btn-primary" title="Si quieres pagar con tu tarjeta de crédito, esta es tu opción" type="submit" value="Pagar en línea" name="pagar">
					<?php endif; ?>
				</fieldset>
			</div>
		</form>
	</div>
</div>

<?php if ($this->session->userdata('eventual')): ?>
<input type="hidden" id="redirect" value="<?php echo site_url('eventual/express') ?>">
<?php endif; ?>


