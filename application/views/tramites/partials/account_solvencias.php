<div class="panel panel-default panel-table">
	<table class="table center datatable table-striped table-default">
		<thead>
			<tr>
				<th>N° de cuenta</th>
				<th>Acción</th>
			</tr>
		</thead>
		<tbody>

			<?php foreach($cuentas as $account): ?> 
				<?php $pass = (is_array($id_tax_type)) ? in_array($account->id_tax_type, $id_tax_type) : $account->id_tax_type == $id_tax_type; ?>
				<?php if ($pass): ?>
				<tr>
					<td><span title="<?php echo strtolower($account->name) ?>"><?php echo $account->tax_account_number?></span></td>
					<td>
						<button type="button" class="btn btn-default btn-md btn-modal" title="Verificar requisitos" data-toggle="modal" data-target="#modal-solvencia">
							<span class="glyphicon glyphicon-new-window span-data" 
							data-id-tax-type="<?php echo $account->id_tax_type ?>" 
							data-id-tax="<?php echo $account->id_tax ?>" 
							data-tax-account-number="<?php echo $account->tax_account_number?>"></span>
						</button>
					</td>
				</tr>
				<?php endif; ?> 
			<?php endforeach; ?>
		</tbody>
	</table>
	<div class="panel-footer center"><div class="pie">¿No aparece alguna de tus cuentas en tu perfil ? <strong><a href="<?php echo site_url('oficina_principal/nuc')?>">Presiona aqu&iacute;</a></strong></div></div>
</div>