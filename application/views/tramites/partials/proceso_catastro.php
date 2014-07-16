<div class="panel panel-default panel-table">
	<table class="table center datatable table-striped table-default">
		<thead>
			<tr>
				<th>N° de cuenta</th>
				<th>N° de ficha</th>
				<th>Tipo</th>
				<th>Estatus</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($procedimientos as $procedimiento): ?> 
			<tr>
				<td><span title="<?php echo strtolower($tax_types[$procedimiento->id_tax_type]->name) ?>"><?php echo $procedimiento->tax_account_number?></span></td>
				<td><?php echo $procedimiento->cadastral_number ?></td>
				<td><?php echo $procedimiento->type ?></td>
				<td><?php echo $procedimiento->status ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>