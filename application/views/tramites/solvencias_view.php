<h3>Solvencias</h3>

<div class="panel panel-primary tabs-primary">
    <div class="panel-heading">
        <!-- Tabs -->
        <ul class="nav panel-tabs">
        	<li><a href="#"><h3 class="panel-title"><?php echo $taxpayer->firm_name?></h3></a></li>

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
                <?php $this->load->view('tramites/partials/account_solvencias', array('cuentas' => $cuentas, 'id_tax_type' => 1)) ?>
            </div>
            <?php endif; ?>

            <?php if ($tax_types[2]->total): ?>
            <div class="tab-pane" id="inmuebles">
                <?php $this->load->view('tramites/partials/account_solvencias', array('cuentas' => $cuentas, 'id_tax_type' => 2)) ?>
            </div>
            <?php endif; ?>

        </div>
    <!--</div>-->
</div>


<!-- Modal -->
<div class="modal fade" id="modal-solvencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Requisitos de solvencia para la cuenta <span class="tax_account_number"></span></h4>
            </div>
            <div class="modal-body">
                <div class="content-solvencia"></div>
            </div>
            <div class="modal-footer">
                <form method="POST" target="_blank">
                    <input type="hidden" name="id_tax">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="iniciar" disabled>Iniciar trámite</button>
                </form>
            </div>
        </div>
    </div>
</div>