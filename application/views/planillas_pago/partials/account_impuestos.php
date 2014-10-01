<div class="panel panel-default panel-table">
	<table class="table center datatable table-striped table-default">
		<thead>
			<tr>
				<th>N° de cuenta</th>
				<th>Generar</th>
			</tr>
		</thead>
		<tbody>
			
			<?php foreach($cuentas as $account): ?>
				<?php $pass = (is_array($id_tax_type)) ? in_array($account->id_tax_type, $id_tax_type) : $account->id_tax_type == $id_tax_type; ?>
				<?php if ($pass): ?>
				<tr>
					<td><span title="<?php echo strtolower($account->name) ?>"><?php echo $account->tax_account_number?></span></td>
					<td>
						<?php if (isset($cargos[$account->id_tax])): ?>
						<a class="btn btn-default btn-md" title="Elegir cargos" href="<?php echo site_url("planillas_pago/impuestos_confirmation/$account->id_tax") ?>">
							<span class="glyphicon glyphicon-list"></span>
						</a>
					<?php else: ?>
						<span class="glyphicon glyphicon-ok" title="No tiene cargos pendientes por incluir en una planilla"></span>
					<?php endif; ?>
					</td>
				</tr>
				<?php endif; ?> 
			<?php endforeach; ?>
		</tbody>
	</table>
	<div class="panel-footer center"><div class="pie">¿No aparece alguna de tus cuentas en tu perfil ? <strong><a href="<?=site_url()?>/oficina_principal/nuc">Presiona aqu&iacute;</a></strong></div></div>
</div>