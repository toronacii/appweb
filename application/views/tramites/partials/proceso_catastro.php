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
			<?php foreach($procedimientos as $procedimiento): $tax = $this->session->userdata('taxes')[$procedimiento->id_tax]?> 
			<tr>
				<td class="tooltip-breakline"><span title="<?php echo $tax->html_tax_information_condensed ?>"><?php echo $procedimiento->tax_account_number?></span></td>
				<td><?php echo $procedimiento->cadastral_number ?></td>
				<td><?php echo $procedimiento->type ?></td>
				<td><?php echo $procedimiento->status ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>