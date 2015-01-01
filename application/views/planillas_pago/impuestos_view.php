<h3>Planillas de impuestos</h3><?php #var_dump($cargos[790]); print_r($cargos);exit;?>

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
                <?php $this->load->view('planillas_pago/partials/account_impuestos', array('cuentas' => $cuentas, 'id_tax_type' => 1, 'cargos' => $cargos)) ?>
            </div>
            <?php endif; ?>

            <?php if ($tax_types[2]->total): ?>
            <div class="tab-pane" id="inmuebles">
                <?php $this->load->view('planillas_pago/partials/account_impuestos', array('cuentas' => $cuentas, 'id_tax_type' => 2, 'cargos' => $cargos)) ?>
            </div>
            <?php endif; ?>
            
            <?php if ($tax_types[3]->total): # VEHÍCULOS?>
            <div class="tab-pane" id="vehiculos">
                <?php $this->load->view('planillas_pago/partials/account_impuestos', array('cuentas' => $cuentas, 'id_tax_type' => 3, 'cargos' => $cargos)) ?>
            </div>
            <?php endif; ?>

            <?php if ($tax_types[4]->total || $tax_types[5]->total): #PUBLICIDAD FIJA O EVENTUAL?>
            <div class="tab-pane" id="publicidad">
                <?php $this->load->view('planillas_pago/partials/account_impuestos', array('cuentas' => $cuentas, 'id_tax_type' => array(4,5), 'cargos' => $cargos)) ?>
            </div>
            <?php endif; ?>
        </div>
    <!--</div>-->
</div>
