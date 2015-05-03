var statement = angular.module('statement', []);

statement.controller('statementCtrl', ['$scope', 'StatementData', function($scope, StatementData) {
	
	angular.extend($scope, StatementData);

	

}]);