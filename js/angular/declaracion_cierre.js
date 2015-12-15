angular.module('declaracion_cierre', ['utilities'])

.controller('MainController', ['$scope', 'ProccessedData', function ($scope, ProccessedData) {

    _.extend($scope, ProccessedData, {
        statement_edit: {},
        total_final: 0,
        action: 'save',
        show_edit_sttm: show_edit_sttm,
        calculate: calculate,
        apply_changes: apply_changes,
        send: send
    });

    function send(event) {

        var statements = {};

        _.each($scope.previous_statements, function (statement) {
            if (statement.changed) {
                var activities = {};
                _.each(statement.activities, function (activity) {
                    if (activity.income.old !== activity.income.new) {
                        activities[activity.id_tax_classifier] = activity.income.new;
                    }
                });
                statements[statement.id_statement] = activities;
            }
        });

        $('input[name=data]').val(JSON.stringify({
            action: $scope.action,
            statements: statements
        }));
    }

    function show_edit_sttm(statement) {
        $scope.statement_edit = $.extend(true, {}, statement);
        $('#edit-sttm').modal('show');
    }
    
    function apply_changes() {
        var statement = _.find($scope.previous_statements, { id_statement: $scope.statement_edit.id_statement });
        statement.tax_total = _.extend({}, $scope.statement_edit.tax_total);
        statement.activities = _.map($scope.statement_edit.activities, function (activity) {
            return $.extend(true, {}, activity);
        });

        $scope.total_final = _.sum($scope.previous_statements, function (statement) {
            return statement.tax_total.difference;
        });

        statement.changed = true;
    }

    function calculate(statement) {

        _.each(statement.activities, function (activity) {

            activity.caused_tax.new = activity.income.new * activity.aliquot / 100;

        });

        var maxActivity = _.max(statement.activities, function (activity) {
            return activity.caused_tax.new
        });

        if (maxActivity.caused_tax.new < maxActivity.minimun_taxable)
        {
            maxActivity.caused_tax.new = maxActivity.minimun_taxable;
        }

        statement.tax_total.new = _.sum(statement.activities, function (activity) {
            return activity.caused_tax.new;
        });

        statement.tax_total.difference = statement.tax_total.new - statement.tax_total.old;
    }

}])

.factory('ProccessedData', ['Data', function (Data) {

    function get_description() {
        $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        return $months[this.month - 1] + " " + this.fiscal_year;
    }

    Data.previous_statements = _.chain(Data.previous_statements)
        .map(function (sttm) {

            _.extend(sttm, {
                description: get_description.bind(sttm)(),
                total_income: _.sum(sttm.classifiers, 'income'),
                tax_total: {
                    old: parseFloat(sttm.tax_total),
                    new: parseFloat(sttm.tax_total),
                    difference: 0
                },
                have_changes: function () {
                    return !!_.find(this.activities, function (activity) {
                        return activity.income.old !== activity.income.new;
                    });
                },
                changed: false,
                activities: _.chain(sttm.activities)
                    .map(function (activity) {

                        return _.extend(activity, {
                            income: {
                                old: parseFloat(activity.income),
                                new: parseFloat(activity.income)
                            },
                            caused_tax: {
                                old: parseFloat(activity.caused_tax),
                                new: parseFloat(activity.caused_tax)
                            }
                        });

                    })
                    .sortBy('code')
                    .value()
            });

            return sttm;

        })
        .sortBy('month')
        .value();

    return Data;

}])

