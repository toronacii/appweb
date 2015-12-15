angular.module('utilities', [])

.filter('number_format', function () {
    return function (number, decimals, dec_point, thousands_sep) {
        decimals = decimals || 2;
        dec_point = dec_point || ',';
        thousands_sep = thousands_sep || '.';
        number = number || 0;
        return number_format(number, decimals, dec_point, thousands_sep);
    }
})

.directive('currency', ['$filter', function ($filter) {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attr, ctrl) {
            var key;
            element.on('keypress', function (event) {
                key = event.which || event.keyCode || 0;
                //console.log(key);
                return (
	            	key === 8 || key === 9 ||
	                (key >= 48 && key <= 57)
	            );
            });

            ctrl.$formatters.unshift(function (value) {
                return $filter('number_format')(value);
            });

            ctrl.$parsers.unshift(function (value) {
                var lastNumber = "";
                if (key >= 48 && key <= 57) {
                    var lastNumber = value.substr(-1, 1);
                    value = value.substr(0, value.length - 1);
                }
                value = value.replace(/,|\./g, "") + lastNumber;
                value = round(value * Math.pow(10, -2), 2);
                element.val($filter('number_format')(value, 2, ',', '.'));

                return value;
            });
        }
    };
}])

.directive('title', [function () {

    return {
        restrict: 'A',
        link: function(scope, element, attrs)
        {
            $(element).tooltip();
        }
    }

}])