<h3>Histórico de trámites</h3>

<div class="panel panel-primary tabs-primary">
    <div class="panel-heading">
        <!-- Tabs -->
        <ul class="nav panel-tabs">
        	<li><a href="#"><h3 class="panel-title"><?php echo $taxpayer->firm_name?></h3></a></li>
             <li class="pull-right"><a href="#retiro" data-toggle="tab">Retiros <span class="badge"><?php echo count($tramites_retiro) ?></span></a></li>
             <li class="pull-right"><a href="#solvencias" data-toggle="tab">Solvencias <span class="badge"><?php echo count($tramites) ?></span></a></li>          
        </ul>
        <div class="text-center hint-caption">
            Estados de los tramites: "Aprobado"= Debe esperar entre 24 y 48 horas, "Listo para retirar"= Debe retirarlo por las taquillas con los recaudos solicitados
        </div>

    </div>
    <!--<div class="panel-body">-->
        <div class="tab-content">

            <div class="tab-pane active" id="solvencias">
                
                <div class="panel panel-default panel-table">
                    <?php if ($tramites): ?>
                    <table class="table center datatable table-striped table-default">
                        <thead>
                            <tr>
                                <th>N° de cuenta</th>
                                <th>N° de trámite</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Estatus</th>
                                <th>Reimprimir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($tramites as $tramite): $tax = $this->session->userdata('taxes')[$tramite->id_tax]?> 
                            <tr>
                                <td class="tooltip-breakline"><span title="<?php echo $tax->html_tax_information_condensed ?>"><?php echo $tramite->tax_account_number?></span></td>
                                <td><?php echo $tramite->request_code ?></td>
                                <td><?php echo date('d/m/Y', strtotime($tramite->request_date)) ?></td>
                                <td>
                                <?php if ($tramite->id_tax_type == 1): ?>
                                    Solvencias de actividades económicas
                                <?php else: ?>
                                    Solvencia de inmuebles urbanos
                                <?php endif; ?>
                                </td>
                                <td><?php echo $tramite->status ?></td>
                                <td><a href="<?php echo site_url("tramites/imprimir/$tramite->id") ?>" class="btn btn-info" target="_blank" title="Imprimir">
                                    <li class="fa fa-file-text-o fa-lg"></li></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <br>
                        <div class="text-center">No hay trámites en el histórico</div>
                    <?php endif; ?>
                </div>

            </div>
            <!-- ############  RETIRO  ##########################-->
            <div class="tab-pane active" id="retiro">
                
                <div class="panel panel-default panel-table">
                    <?php if ($tramites_retiro): ?>
                    <table class="table center datatable table-striped table-default">
                        <thead>
                            <tr>
                                <th>N° de cuenta</th>
                                <th>N° de trámite</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Estatus</th>
                                <th>Reimprimir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($tramites_retiro as $tramite): $tax = $this->session->userdata('taxes')[$tramite->id_tax]?> 
                            <tr>
                                <td class="tooltip-breakline"><span title="<?php echo $tax->html_tax_information_condensed ?>"><?php echo $tramite->tax_account_number?></span></td>
                                <td><?php echo $tramite->request_code ?></td>
                                <td><?php echo date('d/m/Y', strtotime($tramite->request_date)) ?></td>
                                <td>
                                <?php if ($tramite->id_request_type == 3): ?>
                                    Retiro de Licencias de Actividades Económicas
                                <?php endif; ?>
                                </td>
                                 <td><?php echo $tramite->status ?></td>
                                <td><a href="<?php echo site_url("tramites/imprimir_retiro/$tramite->id") ?>" class="btn btn-info" target="_blank" title="Imprimir">
                                    <li class="fa fa-file-text-o fa-lg"></li></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <br>
                        <div class="text-center">No hay trámites en el histórico</div>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    <!--</div>-->
</div>