<div class="panel panel-default panel-table">
	<table class="table center datatable table-striped table-default">
		<thead>
			<tr>
				<th>N° de cuenta</th>
				<th>N° de ficha</th>
				<th>Tipo</th>
				<th>Fecha</th>
				<th>Fiscal asignado</th>
				<th>Estatus</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($procedimientos as $procedimiento):  $tax = $this->session->userdata('taxes')[$procedimiento->id_tax]?> 
			<tr>
				<td class="tooltip-breakline"><span title="<?php echo $tax->html_tax_information_condensed ?>"><?php echo $procedimiento->tax_account_number?></span></td>
				<td><?php echo $procedimiento->n_procedimiento ?></td>
				<td><?php echo $procedimiento->tipo ?></td>
				<td><?php echo date('d/m/Y', strtotime($procedimiento->fecha)) ?></td>
				<td><?php echo $procedimiento->fiscal_asignado ?></td>
				<td><?php echo $procedimiento->status ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>