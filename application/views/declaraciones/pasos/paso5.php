<div class="row setup-content" id="paso-{{ show_step_four ? 5 : 4 }}">
    <div class="well well-sm">
        <strong>Estimado contribuyente:</strong> A continuación usted podrá colocar los ingresos brutos que desea declarar. Pero primero lea lo siguiente: 
        <ol>
            <li class="texto">Si su cuenta renta es nueva deberá estimar los ingresos de acuerdo a su actividad económica.</li> 
            <li class="texto">Verifique que los montos colocados estén en los códigos correspondientes. Evite errores.</li>
            <li>Al finalizar, presione el botón <a href="#" class="label label-primary activate next">Siguiente</a></li>
        </ol>
        <strong>Unidad tributaria = <span ng-bind="tax_unit.value | number_format"></span></strong>
        <input type="hidden" name="textSubmit" id="textSubmit"/>
        <input type="hidden" name="objGoogleMaps" id="objGoogleMaps"/>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading center"><!--<?php echo $this->statement->get_title_statement($sttm) ?>--></div>
        <table class="table table-declaracion">
            <thead>
                <tr>
                    <th>Código</th>
                    <th><span class="hidden-sm hidden-xs">Actividad</span></th>
                    <th>Monto Declarado</th>
                    <th>Alícuota (%)</th>
                    <th>Mínimo Tributario</th>
                    <th ng-if="have_percent_discount">Impuesto</th>
                    <th ng-if="have_percent_discount">Rebaja (%)</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="activity in tax_activities">
                    <td class="hidden-sm hidden-xs">
                        <strong ng-bind="activity.code"></strong>
                    </td>
                    <td class="visible-sm visible-xs">
                        <strong title="{{ activity.full_title }}" ng-bind="activity.code"></strong>
                    </td>
                    <td>
                        <span class="hidden-sm hidden-xs" title="{{ activity.full_title }}" ng-bind="((! activity.authorized) ? '+ ' : '') + activity.description.substr(0,50) + '...'"></span>
                    </td>
                    <td><input type="text" class="form-control text-center" ng-model="activity.monto" ng-blur="calculate()" currency/></td>
                    <td><strong><span ng-bind="activity.aliquot | number_format"></span></strong></td>
                    <td><strong><span ng-bind="(activity.minimun_taxable * tax_unit.value) | number_format"></span></strong></td>
                    <td ng-if="have_percent_discount"><span class="input-span form-control" ng-bind="activity.tax | number_format"></span></td>
                    <td ng-if="have_percent_discount"><span class="input-span form-control" ng-bind="activity.percent_discount | number_format"></span></td>
                    <td><span class="input-span form-control" ng-bind="activity.total_tax | number_format"></span></td>
                </tr>
            </tbody>
            <tfoot>        
                <tr>
                    <td colspan="2"><strong class="titulillo">TOTAL DE INGRESOS BRUTOS DECLARADOS Bs:</strong></td>
                    <td><span class="input-span form-control" ng-bind="totals.income | number_format"></span></td>
                    <td colspan="{{ have_percent_discount ? 4 : 2 }}">&nbsp;</td>
                    <td align="center">
                        <strong class="titulillo">TOTAL IMPUESTO</strong><br>
                        <span class="input-span form-control" ng-bind="totals.subtotal | number_format"></span>
                    </td>
                </tr>
                <tr ng-repeat="discount in tax_discounts">
                    <td colspan="2"><strong class="titulillo">DESCUENTO <span ng-bind="discount.name | uppercase"></span></strong></td>
                    <td class="push-bottom">
                        <input
                            type="text" 
                            class="form-control text-center" 
                            name="tax_discount[amount_discount][{{ discount.id }}]" 
                            ng-model="discount.amount"
                            ng-blur="calculate()"
                            currency
                        />
                    </td>
                    <td colspan="{{ have_percent_discount ? 4 : 2 }}">&nbsp;</td>
                    <td align="center">
                        <span class="input-span form-control" ng-bind="totals.discount_219 | number_format"></span>
                    </td>
                </tr>
            </tfoot>
            <!--
                <?php if ($tax_discounts): #DESCUENTOS?>
                    <?php foreach ($tax_discounts as $tax_discount): ?>
                        <tr class="trDiscount <?php echo ($tax_discount->type == 0) ? 'type_percent' : 'type_amount' ?>">
                            <td colspan="2"><strong class="titulillo">DESCUENTO <?php echo strtoupper($tax_discount->name) ?></strong></td>
                            <td>&nbsp;</td>
                            <td class="push-bottom">
                            <?php if ($tax_discount->type == 0): ?>
                                <strong class="input-span form-control">
                                    <span class="percent_discount"><?php echo number_format($tax_discount->percent, 2, ',', '.') ?></span>
                                    <span>%</span>
                                </strong>
                            <?php endif; ?>    
                            </td>
                            <td class="push-bottom">
                                <?php if ($tax_discount->type == 0): #DE PORCENTAJES ?>
                                    <span class="tax_discount input-span form-control">0,00</span>
                                    <input type="hidden" name="tax_discount[percent_discount][<?php echo $tax_discount->id ?>]" value="<?php echo 'NULL' ?>">
                                <?php endif; ?>
                                <?php if ($tax_discount->type == 1): #DE MONTO (219) ?>
                                    <input
                                        type="text" 
                                        class="float form-control text-center text-primary tax_discount" 
                                        name="tax_discount[amount_discount][<?php echo $tax_discount->id ?>]" 
                                        id="percent_<?php echo $tax_discount->id ?>"
                                        value="<?php echo ($tax_discount->amount) ? number_format($tax_discount->amount, 2, ',', '.') : '0,00' ?>"
                                        data-type="<?php echo $tax_discount->type ?>"
                                    >
                                <?php endif; ?>
                            </td>
                            <td align="center">
                                <span class="subtotal input-span form-control">0,00</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif;?>
                <?php if ($this->sttm_properties->type == 'FALSE'): #ESTIMADA ?>
                <tr>
                    <td colspan="2"><strong style="font-size:0.9em">INGRESOS DEFINITIVOS <?php echo $this->sttm_properties->fiscal_year - 2 ?> Bs:</strong></td>
                    <td><strong id="sttm_old" style="font-size:0.9em; font-weight: bold" class="text-success"><?php echo number_format($sttm_old,2,',','.') ?></strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                        <strong class="titulillo">IMPUESTO TRIMESTRAL</strong><br>
                        <span id="total_final" class="input-span form-control">0,00</span>
                    </td>
                </tr>
                <?php else: #DEFINITIVA ?>
                <tr>
                    <?php if (is_numeric($this->sttm_properties->month)): #MENSUALES ?>
                        <td colspan="2"></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <strong class="titulillo">TOTAL IMPUESTO MENSUAL</strong><br>
                            <span id="total_final" class="input-span form-control">0</span>
                        </td>
                    <?php else: ?>
                        <td colspan="2"><strong style="font-size:0.9em">IMPUESTO ESTIMADO <?php echo $this->sttm_properties->fiscal_year ?> Bs:</strong></td>
                        <td><strong id="sttm_old" style="font-size:0.9em; font-weight: bold"><?php echo number_format($sttm_old,2,',','.') ?></strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <strong class="titulillo">COMPLEMENTO</strong><br>
                            <span id="total_final" class="input-span form-control">0</span>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endif; ?>
            </tfoot>
            -->
        </table>
    </div>
    <!--
    <div class="pull-right">
        <a class="btn btn-primary btn-lg activate">Anterior</a>
        <a class="btn btn-primary btn-lg activate next">Siguiente</a>
    </div>

    -->
<pre ng-bind="tax_activities | json:spacing"></pre>    
</div>

</form>


