<!--<h3>Declaraciones anteriores</h3>--> <?php #$x = $declaraciones['accounts']['030007421']; unset($declaraciones['accounts']); $declaraciones['accounts']['030007421'] = $x; $declaraciones['accounts'] = array() ?>

<div class="well well-sm">
    <strong>Estimado contribuyente:</strong> esta funcionalidad le permitirá visualizar todas las declaraciones realizadas desde que inició actividades económicas en nuestro municipio.
    <em>Nota: </em>A partir del periodo fiscal 2012 podrá imprimir la declaración que haya realizado por el sistema web en formato PDF. Las declaraciones anteriores a ese año aparecerá una X, porque sólo  podrán ser consultadas.
</div>

<div class="panel panel-primary tabs-primary">
    <div class="panel-heading">
        <!-- Tabs -->
        <ul class="nav panel-tabs">
            <li><a href="#"><h3 class="panel-title"><?php echo $taxpayer->firm_name?></h3></a></li>

            <?php 
                if (($total = count($declaraciones['accounts'])) == 1)
                    $tax_account_number = array_keys($declaraciones['accounts'])[0]
            ?>

            <li class="active pull-right">
                <a href="#actividades_economicas" data-toggle="tab">
                    <?php if (isset($tax_account_number)): ?>
                        <?php echo $tax_account_number ?>
                    <?php else: ?>
                        Actividades Económicas <span class="badge"><?php echo $total ?></span>
                    <?php endif; ?>
                </a>
            </li>      
            
        </ul>
    </div>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="actividades_economicas">
                <?php if (count($declaraciones['accounts']) > 1): ?>
                <!-- ACCORDION -->
                <div class="panel-group" id="accordion_statement">
                    <?php foreach ($declaraciones['accounts'] as $tax_account_number => $cuentas): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion_statement" href="#collapse<?php echo $tax_account_number ?>">
                                    <?php echo $tax_account_number ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse<?php echo $tax_account_number ?>" class="panel-collapse collapse">
                            <div class="panel-body">
                                <?php $this->load->view('declaraciones/table_anteriores_view', array('cuentas' => $cuentas)) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- FIN ACCORDION -->
                <?php elseif (count($declaraciones['accounts']) == 1): ?>
                    <?php $this->load->view('declaraciones/table_anteriores_view', array('cuentas' => $declaraciones['accounts'][$tax_account_number])) ?>
                <?php else: ?>
                    <div class="center">No hay declaraciones anteriores</div>
                <?php endif; ?>
                <div class="panel-footer center">¿No aparece alguna de tus cuentas en tu perfil ? <strong><a href="<?php echo site_url('oficina_principal/nuc') ?>">Presiona aquí</a></strong></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-declaraciones">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Detalle de declaración número: <span></span></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

