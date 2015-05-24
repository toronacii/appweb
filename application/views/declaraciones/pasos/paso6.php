    <div class="row setup-content" id="paso-{{ show_step_four ? 6 : 5 }}">
        <div class="panel panel-primary">
            <div class="panel-heading center">Resumen</div>
            <table class="table" id="table_resumen">
                <thead>
                <tr>
                    <th>Código</th>
                    <th><span class="hidden-sm hidden-xs">Actividad</span></th>
                    <th>Monto Declarado</th>
                    <th>Impuesto Anual</th>
                </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="activity in tax_activities">
                        <td><strong ng-bind="activity.code"></strong></td>
                        <td><span class="hidden-sm hidden-xs" title="{{ activity.full_title }}" ng-bind="((! activity.authorized) ? '+ ' : '') + activity.description.substr(0,50) + '...'"></span></td>
                        <td><span ng-bind="activity.monto | number_format"></span></td>
                        <td><span ng-bind="activity.total_tax | number_format"></span></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"><strong class="titulillo">TOTALES</strong></td>
                        <td ng-bind="totals.income | number_format"></td>
                        <td ng-bind="totals.subtotal | number_format"></td>
                    </tr>
                    <tr ng-repeat="discount in tax_discounts">
                        <td colspan="2"><strong class="titulillo">DESCUENTO <span ng-bind="discount.name | uppercase"></span></strong></td>
                        <td ng-bind="discount.amount | number_format"></td>
                        <td ng-bind="totals.discount_219 | number_format"></td>
                    </tr>
                    <tr ng-if="is_monthly">
                        <td colspan="3">&nbsp;</td>
                        <td>
                            <strong class="titulillo">TOTAL IMPUESTO MENSUAL</strong><br>
                            <span ng-bind="totals.total | number_format"></span>
                        </td>
                    </tr>
                    <tr ng-if="! is_monthly">
                        <td colspan="2"><strong style="font-size:0.9em">IMPUESTO ESTIMADO <span ng-bind="sttm_properties.fiscal_year"></span> Bs:</strong></td>
                        <td><strong style="font-size:0.9em; font-weight: bold" ng-bind="sttm_old | number_format"></strong></td>
                        <td>
                            <strong class="titulillo">COMPLEMENTO</strong><br>
                            <span ng-bind="totals.total | number_format"></span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-lg activate">Anterior</a>
            <a class="btn btn-primary btn-lg finalize guardar">Guardar</a>
            <a class="btn btn-primary btn-lg finalize liquidar">Declarar</a>
        </div>
    </div>

    <!--<pre ng-bind="tax_activities | json:spacing"></pre>-->

</div><!-- END statementCtrl -->
