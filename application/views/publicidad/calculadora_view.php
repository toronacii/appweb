<div class="well well-sm">
    Instrucciones ... <br>
    <strong>Unidad Tributaria: </strong><span><?php echo number_format($tax_unit, 2, ',', '.' ) ?></span>
    <input type="hidden" value="<?php echo $tax_unit ?>" id="tax_unit" >
</div>

<div class="panel panel-primary tabs-primary">
    <div class="panel-heading">
        <!-- Tabs -->
        <ul class="nav panel-tabs">
        	<li><a href="#"><h3 class="panel-title">Calculadora de publicidad</h3></a></li>
            <li class="pull-right"><a href="#eventual" data-toggle="tab">Publicidad eventual<span class="badge"></span></a></li>
            <li class="active pull-right"><a href="#fija" data-toggle="tab">Publicidad fija<span class="badge"></span></a></li>
        </ul>
    </div>
    <!--<div class="panel-body">-->
        <div class="tab-content">
            <div class="tab-pane active" id="fija">
            	<?php $this->load->view('publicidad/partials/calculadora', array('classifiers' => $classifiers->fija, 'type' => 'fija')) ?>
            </div>
            <div class="tab-pane" id="eventual">
				<?php $this->load->view('publicidad/partials/calculadora', array('classifiers' => $classifiers->eventual, 'type' => 'eventual')) ?>
            </div>
        </div>
    <!--</div>-->
</div>
