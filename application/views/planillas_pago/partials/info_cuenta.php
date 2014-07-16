<div class="panel panel-primary">
	<div class="panel-heading center">Información de la cuenta</div>
	<ul class="list-group">
		<li class="list-group-item">
			<div class="row">
				<div class="col-md-6"><strong>Razón social</strong></div>
				<div class="col-md-6"><?php echo $taxpayer->firm_name ?></div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-md-6"><strong>C.I. / RIF</strong></div>
				<div class="col-md-6"><?php echo $taxpayer->rif ?></div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-md-6"><strong>Número de contribuyente</strong></div>
				<div class="col-md-6"><?php echo $taxpayer->id_taxpayer ?></div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-md-6"><strong>Tipo de impuesto</strong></div>
				<div class="col-md-6"><?php echo $tax_types[$tax->id_tax_type]->name ?></div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-md-6"><strong>Dirección fiscal</strong></div>
				<div class="col-md-6"><?php echo $tax->address ?></div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-md-6"><strong>N° cuenta renta</strong></div>
				<div class="col-md-6"><?php echo $tax->rent_account ?></div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-md-6"><strong>N° cuenta nueva</strong></div>
				<div class="col-md-6"><?php echo $tax->tax_account_number ?></div>
			</div>
		</li>
	</ul>
</div>