<div class="row setup-content" id="paso-4">
    <div class="well well-sm">
        <strong>Estimado contribuyente:</strong> con el propósito de mejorar nuestra gestión, los invitamos a seleccionar la actividad 
        que realiza específicamente en el municipio. Este solicitud de información es de carácter referencial y no afectará en absoluto su declaración.
        Al finalizar, presione el botón <a href="#" class="label label-primary activate next">Siguiente</a>
    </div>
    <div id="activitiesSpecified" class="validate-paso-4">
        <div ng-repeat="activity in tax_activities" class="col-md-{{ 12 / ((tax_activities.length > 4) ? 4 : tax_activities.length) }} activitySpecified"> 
            <div class="panel" ng-class="{'panel-primary': activity.authorized, 'panel-danger': ! activity.authorized}">
                <div class="panel-heading">
                    <strong ng-bind="activity.code"></strong>
                    <button ng-if="! activity.authorized" type="button" class="close" aria-hidden="true" ng-click="removeActivitiesFromSpecialized(activity)">×</button>
                </div>
                <div class="list-group">
                    <div class="list-group-item" ng-repeat="special in activity.specialized">
                        <select class="select form-control validate" data-validate-rules="required"
                            ng-model="special.selected"
                            ng-options="item.id as item.name for item in special.items"
                            ng-disabled="special.items.length === 0"
                            ng-change="selectSpecialized(activity.specialized, $index)"
                            name="{{ ($last && special.selected) ? 'last_children[' + special.selected + ']' : '' }}">
                            <option value="">Seleccione</option>
                        </select>
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
<pre ng-bind="tax_activities | json:spacing"></pre>
