var statement = angular.module('statement', []);

statement.service('Activities', [function() {
	return function(scope) {

		var removeActivitiesRepeated = function () {
			scope.activities = _.filter(scope.activities, function(activity){
				return ! _.some(scope.tax_activities, {id: activity.id});
			});
		};

		var passActivities = function(from, to) {
			var activities = [];
			_.each(scope[from], function(activity) {
				if (activity.selected) {
					activity.selected = false;
					scope[to].push(activity);
				} else {
					activities.push(activity);
				}
			});
			scope[from] = activities;
			scope[to] = _.sortByOrder(scope[to], ['authorized', 'code'], [false, true]);
		};

		var init = (function() {
			removeActivitiesRepeated();
			var description = (scope.sttm_properties.fiscal_year > 2010) ? 'description' : 'name';
			_.each(_.union(scope.activities, scope.tax_activities), function(activity){
				activity.full_title = activity[description] + " (Alicuota: " + number_format(activity.aliquot, 2, ',', '.') + ")";
			});
		})();

		return {
			addActivities: function() {
				passActivities('activities', 'tax_activities');
			},
			removeActivities: function() {
				passActivities('tax_activities', 'activities');
			}
		}
	}
}]);

statement.controller('statementCtrl', ['$scope', 'StatementData', 'Activities', function($scope, StatementData, Activities) {
	console.log(StatementData);
	
	angular.extend($scope, StatementData);
	var activities = new Activities($scope);
	angular.extend($scope, activities);

}]);