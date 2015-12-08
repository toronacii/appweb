angular.module('declaracion_cierre', [])

.controller('MainController', ['$scope', 'Data', function ($scope, Data) {

    _.extend($scope, Data, {
        edit: {
            statement: {}
        },
        showEditSttm: showEditSttm

    });

    function showEditSttm(statement) {
        $scope.edit.statement = _.extend({}, statement);
        $('#edit-sttm').modal('show');
    }

}])

.filter('number_format', function ()
{
    return function (number, decimals, dec_point, thousands_sep)
    {
        var decimals = decimals || 2,
            dec_point = dec_point || ',',
            thousands_sep = thousands_sep || '.';

        return number_format(number, decimals, dec_point, thousands_sep);
    }
})

