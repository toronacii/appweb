<h3>Planilla unificada</h3><?php #d($_SESSION) ?>

<div class="panel panel-primary tabs-primary">
    <div class="panel-heading">
        <!-- Tabs -->
        <ul class="nav panel-tabs">
        	<li><a href="#"><h3 class="panel-title"><?php echo $taxpayer->firm_name ?></h3></a></li>
            <li class="pull-right active"><a href="#cuentas" data-toggle="tab">Cuentas con cargos <span class="badge"><?php echo count($cargos) ?></span></a></li>
         </ul>
    </div>
    <!--<div class="panel-body">-->
        <div class="tab-content">
            <div class="tab-pane active" id="cuentas">
                <form action="<?php echo site_url('planillas_pago/generar_planilla_unificada') ?>" method="POST" target="_blank">
                    <input type="hidden" name="action" id="action" value="">
                	<div class="panel panel-default panel-table" id="div_unificada">
                        <?php if (count($cargos) > 0 ) : ?>
                        <table class="table center table-striped table-default table-hover">
                            <thead>
                                <tr>
                                    <th>N° de cuenta</th>
                                    <th class="hidden-sm hidden-xs">Tipo de Impuesto</th>
                                    <th><input type="checkbox" id="fecha" title="Marcar / desmarcar todos"></th>
                                    <th>Deuda hasta la fecha</th>
                                    <th><input type="checkbox" id="completo" title="Marcar / desmarcar todos"></th>
                                    <th>Deuda año completo</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php foreach($cargos as $id_tax => $cargo): $tax = $cuentas[$id_tax]; #var_dump($cuentas[$id_tax]) ; exit;?> 
                                    <tr>
                                        <td><span><?php echo $tax->tax_account_number?></span></td>
                                        <td class="hidden-sm hidden-xs"><?php echo $tax_types[$tax->id_tax_type]->name ?></td>
                                        <th><input class="fecha" type="checkbox" name="cuentas[<?php echo $id_tax ?>]" value="1" monto="<?php echo $cargo->fecha_actual ?>" title="<?php echo number_format($cargo->fecha_actual, 2, ',', '.') ?>"></th>
                                        <td><?php echo number_format($cargo->fecha_actual, 2, ',', '.') ?></td>
                                        <th><input class="completo" type="checkbox" name="cuentas[<?php echo $id_tax ?>]"  value="0"  monto="<?php echo $cargo->fecha_completa ?>" title="<?php echo number_format($cargo->fecha_completa, 2, ',', '.') ?>"></th>
                                        <td><?php echo number_format($cargo->fecha_completa, 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="panel-footer center">
                            <fieldset>
                                <input class="btn btn-success" title="Imprime la planilla si vas a pagar por el banco" type="submit" value="Imprimir planilla" name="imprimir">
                                <input class="btn btn-primary" title="Si quieres pagar con tu tarjeta de crédito, esta es tu opción" type="submit" value="Pagar en línea" name="pagar">
                                <span  class="alert alert-success" style="padding: 8px 12px">Total a pagar: <strong><span id="total">0,00</span></strong> Bs.F</span>
                            </fieldset>
                        </div>
                        <?php else: ?>
                        <div class="center">
                            No posee cargos para generar alguna planilla unificada
                        </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    <!--</div>-->
</div>