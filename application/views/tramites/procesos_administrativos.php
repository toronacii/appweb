<h3>Procesos administrativos</h3>

<div class="panel panel-primary tabs-primary">
    <div class="panel-heading">
        <!-- Tabs -->
        <ul class="nav panel-tabs">
        	<li><a href="#"><h3 class="panel-title"><?php echo $taxpayer->firm_name?></h3></a></li>

            <?php if ($count_f = count($procedimientos['fiscalizacion'])): ?>
                <li class="pull-right"><a href="#fiscalizacion" data-toggle="tab">Fiscalizaciones <span class="badge"><?php echo $count_f ?></span></a></li>
            <?php endif; ?>

			<?php if ($count_a = count($procedimientos['auditoria'])): ?>
            	<li class="pull-right active"><a href="#auditorias" data-toggle="tab">Auditorias <span class="badge"><?php echo $count_a ?></span></a></li>
            <?php endif; ?>

        </ul>
    </div>
    <!--<div class="panel-body">-->
        <div class="tab-content">

            <?php if ($count_a): ?>
            <div class="tab-pane active" id="auditorias">
                <?php $this->load->view('tramites/partials/proceso_auditoria', array('procedimientos' => $procedimientos['auditoria'])) ?>
            </div>
            <?php endif; ?>

            <?php if ($count_f): ?>
            <div class="tab-pane" id="fiscalizacion">
                <?php $this->load->view('tramites/partials/proceso_fiscalizacion', array('procedimientos' => $procedimientos['fiscalizacion'])) ?>
            </div>
            <?php endif; ?>

            <?php if ($count_a + $count_f == 0): ?>
                <br>
                <div class="text-center">No tiene procesos administrativos pendientes</div>
            <?php endif; ?>

        </div>
    <!--</div>-->
</div>