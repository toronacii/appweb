<script>
    angular.module('declaracion_cierre').factory('Data', function () {
        var data = <?php echo json_encode($data) ?>;

        function getDescription()
        {
            $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

            return $months[this.month - 1] + " " + this.fiscal_year;
        }

        data.previous_statements = _.chain(data.previous_statements)
            .map(function(sttm) {
            
                Object.defineProperties(sttm, {
                    "description": {
                        value: getDescription.bind(sttm)()
                    },
                    "total_income": {
                        value: _.sum(sttm.classifiers, 'income')
                    }
                });

                sttm.tax_total_new = sttm.tax_total;
                sttm.diferencia = sttm.tax_total - sttm.tax_total_new;

                sttm.activities = _.sortBy(sttm.activities, 'code');

                return sttm;

            })
            .sortBy('month')
            .value();

        return data;
    });
</script>

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
                ng-class="{ 'hover': statement.hover }">
                <div class="col-xs-3" ng-bind="statement.description"></div>
                <div class="col-xs-3" ng-bind="statement.tax_total | number_format"></div>
                <div class="col-xs-3" ng-bind="statement.tax_total_new | number_format"></div>
                <div class="col-xs-3" ng-bind="statement.diferencia | number_format"></div>

                <div class="overlay text-center" ng-click="showEditSttm(statement)">
                    <span>Ver / Editar</span>
                </div>
            </div>
        </div>

        <div class="row foot">
            <div class="col-xs-offset-9 col-xs-3" ng-bind="total_final | number_format"></div>
        </div>

    </div>

    <div class="modal fade" id="edit-sttm" tabindex="-1" role="dialog" aria-labelledby="edit-sttm-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="edit-sttm-label">Editar Declaracion de {{ currentEditSttm.description }}</h4>
                </div>
                <div class="modal-body">
                    
                    <strong>Unidad Tributaria: {{ currentEditSttm.tax_unit | number_format }}</strong>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Actividad</th>
                                <th>Monto declarado</th>
                                <th>Alicuota</th>
                                <th>Impuesto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="activity in currentEditSttm.activities">
                                <td ng-bind="activity.code"></td>
                                <td ng-bind="activity.income | number_format"></td>
                                <td ng-bind="activity.aliquot | number_format"></td>
                                <td ng-bind="activity.caused_tax | number_format"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

</div>
