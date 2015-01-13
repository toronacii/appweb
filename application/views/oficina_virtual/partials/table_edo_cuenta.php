<div class="panel panel-default panel-table">
	<table class="table center datatable table-striped table-default">
		<thead>
			<tr>
				<th>N° de cuenta</th>
				<th>Estado de cuenta hasta la fecha</th>
				<th>Estado de cuenta año completo</th>
			</tr>
		</thead>
		<tbody>

			<?php 
			dd($cuentas);
			if ($id_tax_type == 3)
			{
				foreach($cuentas as $account)
				{
					d($account);
				}
				exit;
			}
			?>

			<?php foreach($cuentas as $account):?> 
				<?php $pass = (is_array($id_tax_type)) ? in_array($account->id_tax_type, $id_tax_type) : $account->id_tax_type == $id_tax_type; ?>
				<?php if ($pass):  ?>
				<tr>
					<td><span title="<?php echo getHtmlTaxInformationCondensed($account->tax_information_condensed) ?>"><?php echo $account->tax_account_number?></span></td>
					<td><a href='<?php echo site_url("edocuenta/right/$account->tax_account_number/1")?>' rel="tooltip" title="Verifique su Estado de Cuenta hasta la fecha" target="_blank"><?php echo number_format($account->total_edocuenta,2,',','.')?></a></td>
					<td><a href='<?php echo site_url("edocuenta/right/$account->tax_account_number/2")?>' rel="tooltip" title="Verifique su Estado de Cuenta del año" target="_blank"><?php echo number_format($account->total_edocuenta2,2,',','.')?></a></td>
				</tr>
				<?php endif; ?> 
			<?php endforeach; ?>
		</tbody>
	</table>
	<!--<div class="panel-footer center"><div class="pie">¿No aparece alguna de tus cuentas en tu perfil ? <strong><a href="<?=site_url()?>/oficina_principal/nuc">Presiona aqu&iacute;</a></strong></div></div>-->
</div>