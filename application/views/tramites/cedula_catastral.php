<h3>Cedula catastral</h3>

<div class="panel panel-primary tabs-primary">
    <div class="panel-heading">
        <!-- Tabs -->
        <ul class="nav panel-tabs">
        	<li><a href="#"><h3 class="panel-title"><?php echo $taxpayer->firm_name?></h3></a></li>

            <?php if ($count_c = count($catastro)): ?>
                <li class="pull-right"><a href="#catastro" data-toggle="tab">Cédula catastral <span class="badge"><?php echo $count_c ?></span></a></li>
            <?php endif; ?>

        </ul>
    </div>
    <!--<div class="panel-body">-->
        <div class="tab-content">

            <?php if ($count_c): ?>
            <div class="tab-pane" id="catastro">
                <?php $this->load->view('tramites/partials/proceso_catastro', array('procedimientos' => $catastro)) ?>
            </div>
            <?php endif; ?>

            <?php if ($count_c == 0): ?>
                <br>
                <div class="text-center">No tiene procesos de cedula catastral pendientes</div>
            <?php endif; ?>

        </div>
    <!--</div>-->
</div>