<script> angular.module('declaracion_cierre').factory('Data', function () { return <?php echo json_encode($data) ?> }); </script>

<div ng-app="declaracion_cierre" ng-controller="MainController">

    <div class="table">

        <div class="row head">
            <div class="col-xs-3">Declaración</div>
            <div class="col-xs-3">Impuesto</div>
            <div class="col-xs-3">Impuesto Revisado</div>
            <div class="col-xs-3">Diferencia</div>
        </div>

        <div class="body">
            <div class="row"
                ng-repeat="statement in previous_statements"
                ng-mouseenter="statement.hover = true"
                ng-mouseleave="statement.hover = false"
                ng-class="{ 'hover': statement.hover, 'changed': statement.changed }">
                <div class="col-xs-3" ng-bind="statement.description"></div>
                <div class="col-xs-3" ng-bind="statement.tax_total.old | number_format"></div>
                <div class="col-xs-3" ng-bind="statement.tax_total.new | number_format"></div>
                <div class="col-xs-3" ng-bind="statement.tax_total.difference | number_format"></div>

                <div class="overlay text-center" ng-click="show_edit_sttm(statement)">
                    <span>Ver / Editar</span>
                </div>
            </div>
        </div>

        <div class="row foot">
            <div class="col-xs-offset-9 col-xs-3">
                <strong ng-bind="total_final | number_format"></strong>
            </div>
        </div>

    </div>

    <div class="col-md-12">
        <form method="post" target="blank" action="<?php echo site_url("declaraciones/declare_closing") ?>" ng-submit="send()">
            <input name="data" type="hidden"/>
            <div class="pull-right">
                <!--<button class="btn btn-primary btn-lg" ng-click="action = 'save'">Guardar</button>-->
                <button class="btn btn-primary btn-lg" ng-click="action = 'liquid'">Declarar</button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="edit-sttm" tabindex="-1" role="dialog" aria-labelledby="edit-sttm-label">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="edit-sttm-label">Editar Declaracion de {{ statement_edit.description }}</h4>
                </div>
                <div class="modal-body">

                    <strong>Unidad Tributaria: {{ statement_edit.tax_unit | number_format }}</strong>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Monto declarado</th>
                                <th>Alicuota (%)</th>
                                <th>Minimo tributario</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="activity in statement_edit.activities">
                                <td><span ng-bind="activity.code" title="{{ activity.description }}" data-placement="right"></span></td>
                                <td>
                                    <input type="text" class="form-control" ng-model="activity.income.new" ng-blur="calculate(statement_edit)" currency />
                                </td>
                                <td ng-bind="activity.aliquot | number_format"></td>
                                <td ng-bind="activity.minimun_taxable | number_format"></td>
                                <td><span class="input-span form-control" ng-bind="activity.caused_tax.new | number_format"></span></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4"></td>
                                <td>
                                    <span class="input-span form-control" ng-bind="statement_edit.tax_total.new | number_format"></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" ng-click="apply_changes()" data-dismiss="modal" ng-disabled="!statement_edit.have_changes()">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

</div>
