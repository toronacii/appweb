<h3>Confirmación</h3>

<div class="col-md-12">

	<div class="well well-sm">
		<strong>Verifica en el menú de Planillas en la opción Generadas no pagadas:</strong><br><br>
		<ol>
			<li>Si tienes planillas vencidas debes eliminarlas para liberar los impuestos a pagar</li>
			<li>Si no están vencidas puedes reimprimirlas para presentarla en los bancos o pagar en línea</li>
			<li>Si intentas marcar un aforo principal, sin haber marcado un aforo principal de fecha anterior, se agregará automáticamente a la planilla</li>
		</ol>
	</div>
	<?php if ($tax->id_tax_type == 3): ?>
		<div class="alert alert-info">
			<strong>Nota:</strong> El pago del impuesto de vehiculo no te exime de posibles recálculos cuando retires tu calcomanía en la Oficina de Atención al Contribuyente
		</div>
	<?php endif; ?>

	<div class="clearfix"></div>

	<?php $this->load->view('planillas_pago/partials/info_cuenta', array('taxpayer' => $taxpayer, 'tax' => $tax, 'tax_types' => $tax_types)) ?>

</div>

<form action="<?php echo site_url('planillas_pago/generar_planilla_impuesto') ?>" method="POST" target="_blank">
	<input type="hidden" name="id_tax" value="<?php echo $tax->id_tax ?>">
	<input type="hidden" name="action" id="action" value="">
	<div class="col-md-12">
		<div class="panel panel-primary" id="div_cargos">
			<div class="panel-heading center">Selecciona los cargos a cancelar</div>
			<table class="table center table-striped">
				<thead>
					<tr>
						<th><input type="checkbox" id="all"></th>
						<th>Fecha</th>
						<th>Concepto</th>
						<th>Monto</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($cargos as $cargo): $properties = ($cargo->type == 'A1' ) ? "class='aforo_principal check-alone' data-aforo='" . @explode(" ", $cargo->concept)[1] . "'" : "class='check-alone'" ?>
					<tr>
						<td><input type="checkbox" name="cargos[<?php echo $cargo->id_transaction ?>]" value="<?php echo $cargo->amount ?>" <?php echo @$properties  ?>></td>
						<td><?php echo date('d/m/Y', strtotime($cargo->application_date)) ?></td>
						<td><?php echo $cargo->concept ?></td>
						<td class="cargo"><?php echo number_format($cargo->amount, 2 , ',','.') ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			
			<div class="panel-footer center">
				<fieldset>
					<?php $page = ($this->session->userdata('usuario_appweb')) ? 'planillas_pago/impuestos' : 'eventual/express' ?>
					<a href="<?php echo site_url($page) ?>" class="btn btn-default" title="Selecciona otro impuesto">Volver</a>
					<input  class="btn btn-success" title="Imprime la planilla si vas a pagar por el banco" type="submit" value="Imprimir planilla" name="imprimir">
					<?php if ($this->session->userdata('usuario_appweb')): ?>
					<input  class="btn btn-primary" title="Si quieres pagar con tu tarjeta de crédito, esta es tu opción" type="submit" value="Pagar en línea" name="pagar">
					<?php endif; ?>
					<span class="alert alert-success" style="padding: 8px 12px">Total a pagar: <strong><span id="total">0,00</span></strong> Bs.F</span>
				</fieldset>
			</div>
			
		</div>
	</div>
</form>

<?php if ($this->session->userdata('eventual')): ?>
<input type="hidden" id="redirect" value="<?php echo site_url('eventual/express') ?>">
<?php endif; ?>