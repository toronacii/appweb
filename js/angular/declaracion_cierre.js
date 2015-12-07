angular.module('declaracion_cierre', [])

    .controller('MainController', ['$scope', 'Data', function ($scope, Data) {

        _.extend($scope, Data);

    }]);

