<?php #var_dump($tipos_tasas) ?>

<h3>Planillas de tasas</h3>

<div class="panel panel-primary tabs-primary">
    <div class="panel-heading">
        <!-- Tabs -->
        <ul class="nav panel-tabs">
        	<li><a href="#"><h3 class="panel-title"><?php echo $taxpayer->firm_name?></h3></a></li>

        	<?php if ($tax_types[4]->total || $tax_types[5]->total): ?>
            	<li class="pull-right"><a href="#publicidad" data-toggle="tab">Publicidad <span class="badge"><?php echo $tax_types[4]->total + $tax_types[5]->total ?></span></a></li>
            <?php endif; ?>

            <?php if ($tax_types[3]->total): ?>
            	<li class="pull-right"><a href="#vehiculos" data-toggle="tab">Vehículos <span class="badge"><?php echo $tax_types[3]->total ?></span></a></li>
            <?php endif; ?>
			
			<?php if ($tax_types[2]->total): ?>
            	<li class="pull-right"><a href="#inmuebles" data-toggle="tab">Inmuebles <span class="badge"><?php echo $tax_types[2]->total ?></span></a></li>
            <?php endif; ?>

        	<?php if ($tax_types[1]->total): ?>
            	<li class="active pull-right"><a href="#actividades_economicas" data-toggle="tab">Actividades Económicas <span class="badge"><?php echo $tax_types[1]->total ?></span></a></li>
        	<?php endif; ?>
        	
            
            
        </ul>
    </div>
    <!--<div class="panel-body">-->
        <div class="tab-content">

            <?php if ($tax_types[1]->total): ?>
            <div class="tab-pane active" id="actividades_economicas">
                <?php $this->load->view('planillas_pago/partials/account_tasas', array('cuentas' => $cuentas, 'id_tax_type' => 1)) ?>
            </div>
            <?php endif; ?>

            <?php if ($tax_types[2]->total): ?>
            <div class="tab-pane" id="inmuebles">
                <?php $this->load->view('planillas_pago/partials/account_tasas', array('cuentas' => $cuentas, 'id_tax_type' => 2)) ?>
            </div>
            <?php endif; ?>
            
            <?php if ($tax_types[3]->total): # VEHÍCULOS?>
            <div class="tab-pane" id="vehiculos">
                <?php $this->load->view('planillas_pago/partials/account_tasas', array('cuentas' => $cuentas, 'id_tax_type' => 3)) ?>
            </div>
            <?php endif; ?>

            <?php if ($tax_types[4]->total || $tax_types[5]->total): #PUBLICIDAD FIJA O EVENTUAL?>
            <div class="tab-pane" id="publicidad">
                <?php $this->load->view('planillas_pago/partials/account_tasas', array('cuentas' => $cuentas, 'id_tax_type' => array(4,5))) ?>
            </div>
            <?php endif; ?>

        </div>
    <!--</div>-->
</div>


<!-- Modal -->
<div class="modal fade" id="modalTasas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<form action="<?php echo site_url('planillas_pago/tasas_confirmation'); ?>" method="POST">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel"></h4>
				</div>
				<div class="modal-body">
				    <ul class="list-group">
				    <?php foreach($tipos_tasas as $tasa): ?>

                        <li class="list-group-item tax_type tax_type_<?php echo $tasa->id_tax_type ?>">
                            <div class="radio">
                              <label>
                                <input type="radio" name="id_tasa" id="tasa_<?php echo $tasa->id ?>" value="<?php echo $tasa->id ?>">
                                <?php echo $tasa->name ?>
                              </label>
                            </div>
                        </li>
                        
				    <?php endforeach; ?>
                    </ul>

				<input type="hidden" id="id_tax" name="id_tax">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<input type="submit" class="btn btn-primary" value="Continuar">
				</div>

			</form>
		</div>
	</div>
</div>

