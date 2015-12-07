<script>
    angular.module('declaracion_cierre').factory('Data', function () { return <?php echo json_encode($data) ?> });
</script>

<div ng-app="declaracion_cierre" ng-controller="MainController">

    <div ng-repeat="statement in previous_statements">

        {{ statement.month }}

        <pre>
        {{ statement | json:spacing }}
        </pre>

    </div>

</div>
