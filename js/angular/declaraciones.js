var statement = angular.module('statement', []);

statement.filter('number_format', function(){
	return function(number, decimals, dec_point, thousands_sep) {
		decimals = decimals || 2;
		dec_point = dec_point || ',';
		thousands_sep = thousands_sep || '.';
		number = number || 0;
		return number_format(number, decimals, dec_point, thousands_sep);
	}
});

statement.directive('currency', ['$filter', function($filter) {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attr, ctrl) {
        	var key;
        	element.on('keypress', function(event) {
        		key = event.which || event.keyCode || 0;
        		//console.log(key);
	            return (
	            	key === 8 || key === 9 ||
	                (key >= 48 && key <= 57)
	            );
	        });

        	ctrl.$formatters.unshift(function(value){
        		return $filter('number_format')(value);
        	});

        	ctrl.$parsers.unshift(function(value){
        		var lastNumber = "";
        		if (key >= 48 && key <= 57) {
        			var lastNumber = value.substr(-1, 1);
        			value = value.substr(0, value.length - 1);
        		}
        		value = value.replace(/,|\./g,"") + lastNumber;
        		value = round(value * Math.pow(10,-2), 2);
        		element.val($filter('number_format')(value, 2, ',', '.'));
        		
        		return value;
        	});
        }
    };
}]);

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

statement.service('CalculateTax', [function(){
	return function(scope) {

		var globalMinimun = scope.activities[0].minimun_taxable * scope.tax_unit.value;

		var calculateTaxes = function() {
			_.each(scope.tax_activities, function(activity){
				activity.tax = activity.monto * activity.aliquot / 100;
				activity.total_tax = (100 - activity.percent_discount) * activity.tax / 100;
				
				// CALCULATE MINIMUN FOR STATEMENTS <= 2010
				if (scope.sttm_properties.year <= 2010 && activity.percent_discount < 100) {
					var minimun = activity.minimun_taxable * scope.tax_unit.value;
					if (activity.total_tax < minimun) {
						activity.total_tax = minimun;
					}
				}
				
			});
		}

		var calculateMaxMinimun = function() {
			var maxActivity = _.max(_.filter(scope.tax_activities, function(activity) { 
				return activity.percent_discount < 100
			}), 'tax');

			if (maxActivity.tax < globalMinimun) {
				maxActivity.total_tax = globalMinimun;
			}
		}

		var calculateTotals = function() {
			var income = 0, 
				subtotal = 0,
				discount_219 = 0; 

			_.each(scope.tax_activities, function(activity) {
				income += activity.monto;
				subtotal += activity.total_tax;
			});

			_.each(scope.tax_discounts, function(discount) {
				if (discount.type === 1) {
					discount_219 = subtotal - (discount.amount || 0)
				}
			});

			if (discount_219 < 0) {
				discount_219 = 0;
			}

			scope.totals = {
				income: income,
				subtotal: subtotal,
				discount_219: discount_219,
				total: ((scope.have_discount_219) ? discount_219 : subtotal) - scope.sttm_old
			};
		}

		return {
			calculate: function() {
				calculateTaxes();

				if (scope.sttm_properties.year > 2010) {
					calculateMaxMinimun();
				}

				calculateTotals();
			}
		}
	}
}]);

statement.service('Activities', ['Specialized', 'CalculateTax', function(Specialized, CalculateTax) {
	return function(scope) {

		var data = {};

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
			var description = (scope.sttm_properties.year > 2010) ? 'description' : 'name';
			scope.totals = {};
			_.each(_.union(scope.activities, scope.tax_activities), function(activity) {
				activity.monto = (activity.monto) ? parseFloat(activity.monto) : 0;
				activity.full_title = activity[description] + " (Alicuota: " + number_format(activity.aliquot, 2, ',', '.') + ")";
				activity.authorized = activity.authorized === 't';
				activity.percent_discount = (activity.percent_discount) ? parseFloat(activity.percent_discount) : 0
				data.have_percent_discount = !! (data.have_percent_discount || (activity.percent_discount > 0))
				if (scope.show_step_four) {
					Specialized.addSpecialized(activity);
				}
				if (scope.is_closing) {
					activity.minimun_taxable = 4 * ((new Date()).getMonth() + 1);
				}
			});
		})();

		var calculates = new CalculateTax(scope);

		return angular.extend(data,
			{
				addActivities: function() {
					passActivities('activities', 'tax_activities');
					calculates.calculate();
				},
				removeActivities: function() {
					passActivities('tax_activities', 'activities');
					calculates.calculate();
				},
				removeActivitiesFromSpecialized: function(activity) {
					activity.selected = true;
					this.removeActivities();
				}
			}, Specialized, calculates);
	}
}]);

statement.controller('statementCtrl', ['$scope', 'StatementData', 'Activities', function($scope, StatementData, Activities) {
	StatementData.have_discount_219 = StatementData.tax_discounts.length > 0;
	StatementData.is_monthly = StatementData.sttm_properties.month > 0;
	StatementData.sttm_old = (StatementData.sttm_old) ? parseFloat(StatementData.sttm_old) : 0;
	StatementData.is_closing = StatementData.sttm_properties.closing;
	angular.extend($scope, StatementData);
	var activities = new Activities($scope);
	angular.extend($scope, activities);

	$scope.calculate();

}]);