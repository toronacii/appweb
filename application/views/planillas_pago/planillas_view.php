<h3>Consulta de planillas generadas</h3>

<?php if (count($planillas)): ?>
<div class="panel panel-primary tabs-primary">
    <div class="panel-heading">
        <!-- Tabs -->
        <ul class="nav panel-tabs">
            <?php $cuenta = ($this->session->userdata('eventual')) ? "($tax->tax_account_number)" : "" ?>
        	<li><a href="#"><h3 class="panel-title"><?php echo "$taxpayer->firm_name $cuenta" ?></h3></a></li>

            <?php if ($isset_no_pagadas = (isset($planillas['no_pagadas']) && $planillas['no_pagadas'] > 0)): ?>
            	<li class="pull-right active"><a href="#no_pagadas" data-toggle="tab">No pagadas <span class="badge"><?php echo count($planillas['no_pagadas']) ?></span></a></li>
            <?php endif; ?>
			
			<?php if ($isset_pagadas = (isset($planillas['pagadas']) && $planillas['pagadas'] > 0)): ?>
                <li class="pull-right <?php if (! $isset_no_pagadas ) echo "active" ?>"><a href="#pagadas" data-toggle="tab">Pagadas <span class="badge"><?php echo count($planillas['pagadas']) ?></span></a></li>
            <?php endif; #var_dump($isset_pagadas, $isset_no_pagadas)?>
        	
            
            
        </ul>
    </div>
    <!--<div class="panel-body">-->
        <div class="tab-content">
            <?php if ($isset_pagadas): ?>
            <div class="tab-pane <?php if (! $isset_no_pagadas ) echo "active" ?>" id="pagadas">
            	<?php $this->load->view('planillas_pago/partials/planillas_generadas', array('planillas' => $planillas['pagadas'], 'status' => true)) ?>
            </div>
            <?php endif; ?>
            <?php if ($isset_no_pagadas): ?>
            <div class="tab-pane active" id="no_pagadas">
				<?php $this->load->view('planillas_pago/partials/planillas_generadas', array('planillas' => $planillas['no_pagadas'], 'status' => false)) ?>
            </div>
            <?php endif; ?>
        </div>
    <!--</div>-->
</div>
<?php else: ?>
<h4>No hay planillas cargadas</h4>
<?php endif; ?>