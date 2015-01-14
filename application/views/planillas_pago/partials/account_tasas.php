<div class="panel panel-default panel-table">
	<table class="table center datatable table-striped table-default">
		<thead>
			<tr>
				<th>N° de cuenta</th>
				<th>Tipo tasa</th>
			</tr>
		</thead>
		<tbody>

			<?php foreach($cuentas as $account): ?> 
				<?php $pass = (is_array($id_tax_type)) ? in_array($account->id_tax_type, $id_tax_type) : $account->id_tax_type == $id_tax_type; ?>
				<?php if ($pass): ?>
				<tr class="tooltip-breakline">
					<td><span title="<?php echo $account->html_tax_information_condensed ?>"><?php echo $account->tax_account_number?></span></td>
					<td>
						<button type="button" class="btn btn-default btn-md" data-toggle="modal" title="Elegir tipo de tasa">
							<span class="glyphicon glyphicon-new-window" id_tax_type="<?php echo $account->id_tax_type ?>" id_tax="<?php echo $account->id_tax ?>"></span>
						</button>
					</td>
				</tr>
				<?php endif; ?> 
			<?php endforeach; ?>
		</tbody>
	</table>
	<!--<div class="panel-footer center"><div class="pie">¿No aparece alguna de tus cuentas en tu perfil ? <strong><a href="<?=site_url()?>/oficina_principal/nuc">Presiona aqu&iacute;</a></strong></div></div>-->
</div>