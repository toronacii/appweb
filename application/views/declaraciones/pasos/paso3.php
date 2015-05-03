<div class="row setup-content" id="paso-3">
    <div class="well well-sm">
        A continuación se muestran los grupos autorizados correspondientes a su Licencia de Actividades Económicas. 
        Si has realizado alguna actividad que no se corresponde con estos grupos, 
        puedes seleccionarla del recuadro inferior y declarar los ingresos percibidos por esa actividad adicional. 
        Recuerda que declarar por un grupo no autorizado no convalida el ejercicio de la actividad respectiva, 
        ni exime de las sanciones correspondientes.
        Al finalizar, presione el botón <a href="#" class="label label-primary activate next" data-paso="3">Siguiente</a>
    </div>
    <div class="col-md-6" id="activities"> 
        <div class="panel panel-primary selectTable" id="allActivities">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-2 col-sm-2 title">Actividades</div>
                    <div class="col-md-8 col-sm-8"><input type="text" class="form-control" placeholder="Buscar" ng-model="find_activity"></div>
                    <div class="col-md-2 col-sm-2"><a href="" class="btn btn-success" ng-click="addActivities()">Añadir</a></div>
                </div>                
                <!---->
            </div>

            <div class="list-activities">
                <div class="list-group">
                    <a  ng-repeat="activity in activities | filter:find_activity" href="" class="list-group-item nowrap" title="{{ activity.full_title }}" ng-class="{active: activity.selected}" ng-click="activity.selected = !activity.selected">
                        <strong ng-bind="activity.code"></strong> - <span ng-bind="activity.full_title"></span>
                    </a>
                </div>
            </div>
        </div>     
    </div>
    <div class="col-md-6 selectTable" id="activitiesTaxpayer">
        <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-4 col-sm-4 title">Actividades a declarar</div>
                    <div class="col-md-6 col-sm-6"><input type="text" class="form-control" placeholder="Buscar" ng-model="find_tax_activity"></div>
                    <div class="col-md-2 col-sm-2"><a href="#" class="btn btn-danger" ng-click="removeActivities()">Quitar</a></div>
                </div>                
                <!---->
            </div>

            <div class="list-activities">
                <div class="list-group">

                    <div ng-repeat="activity in tax_activities | filter:find_activity" title="{{ activity.full_title }}">
                        <a ng-if="activity.authorized === 'f'" href="" class="nowrap list-group-item" ng-class="{active: activity.selected}" ng-click="activity.selected = !activity.selected">
                            <strong ng-bind="activity.code"></strong> - <span ng-bind="activity.full_title"></span>
                        </a>
                        <div ng-if="activity.authorized !== 'f'" class="nowrap list-group-item">
                            <strong ng-bind="activity.code"></strong> - <span ng-bind="activity.full_title"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="pull-right">
            <a class="btn btn-primary btn-lg activate">Anterior</a>
            <a class="btn btn-primary btn-lg activate next">Siguiente</a>
        </div>
    </div>
</div>
