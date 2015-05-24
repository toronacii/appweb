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
        <div class="panel-heading center"><span ng-bind="title_statement"></span><!--<?php echo $this->statement->get_title_statement($sttm) ?>--></div>
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
                <tr ng-if="is_monthly">
                    <td colspan="{{ have_percent_discount ? 7 : 5 }}">&nbsp;</td>
                    <td>
                        <strong class="titulillo">TOTAL IMPUESTO MENSUAL</strong><br>
                        <span class="input-span form-control" ng-bind="totals.total | number_format"></span>
                    </td>
                </tr>
                <tr ng-if="! is_monthly">
                    <td colspan="2"><strong style="font-size:0.9em">IMPUESTO ESTIMADO <span ng-bind="sttm_properties.fiscal_year"></span> Bs:</strong></td>
                    <td><strong style="font-size:0.9em; font-weight: bold" ng-bind="sttm_old | number_format"></strong></td>
                    <td colspan="{{ have_percent_discount ? 4 : 2 }}">&nbsp;</td>
                    <td>
                        <strong class="titulillo">COMPLEMENTO</strong><br>
                        <span class="input-span form-control" ng-bind="totals.total | number_format"></span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-md-12">
        <div class="pull-right">
            <a class="btn btn-primary btn-lg activate">Anterior</a>
            <a class="btn btn-primary btn-lg activate next">Siguiente</a>
        </div>  
    </div>
    
</div>

</form>


