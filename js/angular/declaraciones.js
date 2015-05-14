var statement = angular.module('statement', []);

statement.service('Specialized', ['TaxClassifierSpecialized', function(TaxClassifierSpecialized) {

	return {
		addSpecialized: function(activity) {
			if (activity.specialized === undefined)
			{
				activity.specialized = [
					{selected: null, items: this.findSpecialized(activity.ids_specialized)}, 
					{selected: null, items: []}, 
					{selected: null, items: []}, 
					{selected: null, items: []}
				];

				if (activity.last_specialized > 0) {

					for (var i = activity.specialized.length - 1; i > 0; i--) {

						var last_specialized = (id_parent || activity.last_specialized);
						var id_parent = this.findSpecialized(last_specialized)[0].id_parent;
						activity.specialized[i].items = this.findSpecialized(id_parent, true);
						activity.specialized[i].selected = last_specialized;
					}

					activity.specialized[0].selected = id_parent;

				}	
			}
		},
		findSpecialized: function(ids, findChildren) {

			if (findChildren) 
			{
				return _.where(TaxClassifierSpecialized, {'id_parent': ids});
			}

			var ids = _.map((ids + "").split(","), function(id) {
				return parseInt(id.trim()); 	
			});

			var specialized = [];
			for (var i = 0; i < TaxClassifierSpecialized.length && ids.length > 0; i++ ) {
				var index = ids.indexOf(TaxClassifierSpecialized[i].id);
				if (index > -1) {
					ids.splice(index, 1);
					specialized.push(TaxClassifierSpecialized[i]);
				}
			}

			return specialized;
		},
		selectSpecialized: function(specialized, index) {
			for (var i = index + 1; i < specialized.length; i++) {
				if (i === index + 1 && specialized[index].selected) {
					specialized[i] = {
						selected: null, 
						items: this.findSpecialized(specialized[index].selected, true)
					}
				} else {
					specialized[i] = {selected: null, items: []}
				}
			}
		}
	}
}]);

statement.service('Activities', ['TaxClassifierSpecialized', 'Specialized', function(TaxClassifierSpecialized, Specialized) {
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
					if (scope.show_step_four) {
						Specialized.addSpecialized(activity);
					}
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
				activity.authorized = activity.authorized === 't';
				if (scope.show_step_four) {
					Specialized.addSpecialized(activity);
				}
			});
		})();

		return angular.extend(
			{
				addActivities: function() {
					passActivities('activities', 'tax_activities');
				},
				removeActivities: function() {
					passActivities('tax_activities', 'activities');
				},
				removeActivitiesFromSpecialized: function(activity) {
					activity.selected = true;
					this.removeActivities();
				}
			}, Specialized)
	}
}]);

statement.controller('statementCtrl', ['$scope', 'StatementData', 'Activities', function($scope, StatementData, Activities) {
	console.log(StatementData);
	
	angular.extend($scope, StatementData);
	var activities = new Activities($scope);
	angular.extend($scope, activities);

}]);