<div class="panel panel-default panel-table">
	<table class="table center table-hover datatable table-default">
		<thead>
			<tr>
				<?php if (! $this->session->userdata('eventual')): #CONTRIBUYENTE LOGUEADO?>
				<th>N° de cuenta</th>
				<?php endif; ?>
				<th>N° de planilla</th>
				<th>Tipo</th>
				<th>Fecha de emisión</th>
				<th><?php echo ($status) ? 'Fecha de pago' : 'Fecha de vencimiento' ?></th>
				<th>Monto</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($planillas as $planilla): ?> 
			<tr class="<?php echo ($planilla->status == "no pagada" && $planilla->vencida) ? "danger" : "" ?>">
				<?php if (! $this->session->userdata('eventual')): $tax = @$this->session->userdata('taxes')[$planilla->id_tax]?>
				<td class="tooltip-breakline">
					<span title="<?php echo @$tax->html_tax_information_condensed ?>">
						<?php echo $planilla->tax_account_number?>
					</span>
				</td>
				<?php endif; ?>
				<td><?php echo $planilla->invoice_number ?></td>
				<td><?php echo $planilla->tipo ?></td>
				<td><?php echo date('d/m/Y', strtotime($planilla->emision_date)) ?></td>
				<td><?php echo date('d/m/Y', strtotime($planilla->date)) ?></td>
				<td><?php echo number_format($planilla->total_amount, 2, ',', '.') ?></td>
				<td>
					<?php $action = ($planilla->invoice_type == 5) ? 'unificada' : 'imprime_planilla' ?>
					<a href="<?php echo site_url("generar_planilla/$action/$planilla->id") ?>" class="btn btn-info" title="Imprimir Planilla" target="_blank"><li class="fa fa-file-text-o fa-lg"></li></a>
					<?php if (! $this->session->userdata('eventual') && $status && $planilla->control): ?>
					<a href="<?php echo site_url("generar_planilla/imprime_pago_megasoft/$planilla->control") ?>" class="btn btn-success" title="Imprimir Voucher" target="_blank"><li class="fa fa-file-text-o fa-lg"></li></a>
					<?php $control[] = $planilla->control ?>
					<?php endif; ?>
				<?php if (! $status): ?>
					<?php if (! $this->session->userdata('eventual')): # CONTRIBUYENTE LOGUEADO ?> 
					<a href="<?php echo site_url("pago_online/index/{$planilla->id}") ?>" class="btn btn-primary" title="Pagar en línea" target="_blank"><li class="fa fa-money fa-lg"></li></a>
					<?php endif; ?>
					<a href="<?php echo site_url("planillas_pago/delete/{$planilla->id}") ?>" class="btn btn-danger delete_planilla" title="Eliminar"><li class="fa fa-times fa-lg"></li></a>
				<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; if (isset($control)) $this->session->set_userdata("control", $control) ?>
		</tbody>
	</table>
	<!--
	<?php if (! $this->session->userdata('eventual')): ?>
	<div class="panel-footer center"><div class="pie">¿No aparece alguna de tus cuentas en tu perfil ? <strong><a href="<?=site_url()?>/oficina_principal/nuc">Presiona aqu&iacute;</a></strong></div></div>
	<?php endif; ?>
	-->
</div>

<?php if ($this->session->userdata('eventual')): ?>
<input type="hidden" id="redirect" value="<?php echo site_url('eventual/express') ?>">
<?php endif; ?>