<?php #var_dump($classifiers) ?>

<div class="col-md-5" style="margin-top: 15px; margin-bottom: 15px">
	<div class="panel panel-default activities">
        <!-- Default panel contents -->
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-8"><input type="text" class="form-control finder" placeholder="Buscar"></div>
                <div class="col-md-4 pull-right"><a href="#" class="btn btn-success add" data-to="#calculator-<?php echo $type ?>">Añadir</a></div>
            </div>                
            <!---->
        </div>

        <div style="max-height:300px; overflow-y: scroll;overflow-x: hidden">

            <div class="list-group">
            <?php foreach ($classifiers as $objPub): ?>
                <a  href="#"
                    class="list-group-item"
                    style="white-space: nowrap"
                    title="<?php echo $objPub->description ?>"
                    data-code="<?php echo $objPub->code ?>"
                    data-name="<?php echo $objPub->name ?>"
                    data-formula="<?php echo $objPub->formula ?>"
                    data-cant-unit="<?php echo $objPub->cant_unit ?>"
                    data-min-taxable="<?php echo $objPub->min_taxable ?>" >

                        <strong><?php echo "{$objPub->code} - {$objPub->name}" ?> </strong>
                </a>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
 <form action="<?php echo site_url('publicidad/generaPDF') ?>" target="_blank" method="POST">

	<div class="col-md-7" style="margin-top: 15px; margin-bottom: 15px">
		<div class="panel panel-default" class="calculator">
	        <!-- Default panel contents -->
	        <div class="panel-heading">
	            <div class="row">
	                <div class="col-md-offset-6 col-md-3"><a href="#" class="btn btn-danger remove" disabled data-to="#calculator-<?php echo $type ?>">Eliminar fila(s)</a></div>
	                <div class="col-md-3"><button type="submit" class="btn btn-success" disabled>Imprimir</button></div>
	            </div>                
	            <!---->
	        </div>
	        <table class="table table-striped table-hover publicidad" id="calculator-<?php echo $type ?>">
				<thead>
					<tr>
						<th><input type="checkbox" class="selectAll" disabled></th>
						<th>#</th>
			            <th>LARGO</th>
			            <th>ANCHO</th>
			            <th>DIAS</th>
			            <th>CANTIDAD</th>
			            <th>TOTAL</th>
					</tr>
				</thead>
				<tbody><tr id="msjTable"><td colspan="7">Agregue una actividad</td></tr></tbody>
				<tfoot class="hide">
		            <tr>
		                <td colspan="6" class="align-right">SUBTOTAL</td>
		                <td><input id="subtotal" name="subtotal" class="form-control float" readonly></td>
		            </tr>
		            <tr>
		                <td colspan="6" class="align-right"><input type="checkbox" class="check-special" name="check" id="check"/> IMPUESTO ESPECIAL 25%</td>
		                <td><input id="imp_esp" name="imp_esp" class="form-control float" readonly></td>
		            </tr>
		            <tr>
		                <td colspan="6" class="align-right">TOTAL GENERAL</td>
		                <td><input id="total_general" name="total_general" class="form-control float" readonly></td>
		            </tr>
		        </tfoot>
			</table>
		</div>
	</div>

</form>