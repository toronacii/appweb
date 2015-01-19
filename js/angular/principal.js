var app = angular.module('app', ['simplePagination']);

app.directive('onFinishRenderFilters', function ($timeout) {
	return {
		restrict: 'A',
		link: function (scope, element, attr) {
			if (scope.$last === true) {
				$timeout(function () {
					scope.$emit('ngRepeatFinished');
				});
			}
		}
	}
});

app.controller('mainController', ['$scope', '$http', 'Pagination', 'filterFilter',

function ($scope, $http, Pagination, filterFilter) {

	$scope.check = false;

	$scope.pagination = Pagination.getNew(10);
	$scope.loading = false;
	//console.log($scope.pagination);

	$scope.change_more = function()
	{
		$scope.more = {
			'readed' : false,
			'unreaded' : false,
			'all_readed' : false,
			'all_unreaded' : false,
		}
		var news = $('div.new');

		if (news.find('.mark:checked').length)
		{
			$scope.more.readed = !!news.not('.read').find('.mark:checked').length;
			$scope.more.unreaded = !!news.filter('.read').find('.mark:checked').length;
		}
		else
		{
			$scope.more.all_readed = !!news.not('.read').length;
			$scope.more.all_unreaded = !!news.filter('.read').length;
		}
		
	}

	$scope.$on('ngRepeatFinished', function (ngRepeatFinishedEvent) {
		$scope.change_more();
		$scope.checkChecked();
		$scope.checkParent();
	});

	$scope.$watch('search', function(term) {  
	    // Create filtered 
	    $scope.filtered = filterFilter($scope.news, term);

	    if ($scope.filtered !== undefined)
	    {
	    	$scope.pagination.numPages = Math.ceil($scope.filtered.length/$scope.pagination.perPage);
	    	$scope.pagination.totalElements = $scope.filtered.length;
	    	if ($scope.pagination.page >= $scope.pagination.numPages)
	    		$scope.pagination.page = $scope.pagination.numPages - 1;
	    	if ($scope.pagination.page < 0)
	    		$scope.pagination.page = 0;
	    }

	    // Then calculate noOfPages
	    //console.log($scope.news);
	    // 
	    //console.log($scope.pagination);
	    //console.log();
	});

	var change_checks = function(value)
	{
		$('div.new').find('.mark').each(function(){
			$(this).prop('checked', value);
		});

	}

	var change_mark = function(news, type)
	{
		var type = type || 'read';
		$scope.loading = true;
		$.post(site_url + '/oficina_principal/ajax_news', {
			'news' : news,
			'type' : type
		}, function(){
			$scope.get_news();
			$scope.loading = false;
		});
	}

	var get_object_id_news = function(checks)
	{
		var resp = [];
		checks.each(function(){
			//console.log(this);
			resp.push($(this).data('id'));
		});

		return resp;
	}
    
    $scope.get_news = function()
    {
    	$scope.loading = true;
    	$http.get(site_url + '/oficina_principal/api_get_news')
    		.success(function(data){
    			for (i in data)
    			{
    				data[i]['checked'] = false;
    			}
    			$scope.news = data;
    			$scope.filtered = data;
    			
    			$scope.pagination.numPages = Math.ceil($scope.news.length/$scope.pagination.perPage);
    			$scope.pagination.totalElements = $scope.news.length;
    			$scope.loading = false;
    			//console.log(data);
    		})
    		.error(function(data){
    			console.log('Error: ' + data);
    		});
    	
    }

    $scope.get_news();

    $scope.checkAll = function(checkbox)
    {
    	change_checks($(checkbox).is(':checked'));
    	$scope.checkChecked();
    }

    $scope.checkChecked = function()
    {
    	$scope.check = !!$('div.new').find('.mark:checked').length;
    	$scope.change_more();
    }

    $scope.checkParent = function(event)
    {
    	if (event && $(event.target).is('.mark'))
    		event.stopPropagation();
    	$scope.checkAllButton = ($('div.new').find('.mark:checked').length === $('div.new').find('.mark').length);
    	$scope.checkChecked();
    }

    $scope.checkType = function(type, event)
    {
    	var type = type || 'read';
    	change_checks(false);
    	if (type === 'read')
    	{
    		var $match = $('div.new.read').find('.mark');
    	}
    	else
    	{
    		var $match = $('div.new').not('.read').find('.mark');
    	}
    	
    	$match.each(function(){
    		$(this).prop('checked', true);
    	});

    	$scope.checkParent(event);
    	$scope.change_more();
    }

    $scope.changeType = function(type)
    {
    	var news = $('div.new');
    	var checked = !!news.find('.mark:checked').length;
    	var checks;

		if (type === 'read')
		{
			(checked) ? 
			checks = news.not('.read').has('.mark:checked').addClass('read') : 
			checks = news.not('.read').addClass('read');
		}
		else
		{
			(checked) ? 
			checks = news.filter('.read').has('.mark:checked').removeClass('read') : 
			checks = news.filter('.read').removeClass('read');
		}

		$scope.change_more();
		//console.log(get_object_id_news(checks), type);
		change_mark(get_object_id_news(checks), type);
    }

    $scope.showMessage = function(me)
    {
    	var $this = $(me);
    	if (! $this.is('div.new')) $this = $this.parents('div.new');

		var content = $this.data('content');
		
		//console.log(me);

		//console.log($(this).find('span.label-type').data('type'));
		BootstrapDialog.show({
			cssClass: 'dialog-large',
			message: content,
			title: $this.find('span.title').text(),
			type: "type-" + $this.find('span.label-type').data('type'),
			buttons: [{
				label: 'Cerrar',
				cssClass: 'btn-' + $this.find('span.label-type').data('type'),
				action: function(dialog){
					dialog.close();
				}
			}]
		});

		if (! $this.hasClass('read'))
		{
			change_mark([$this.find(':checkbox.mark').data('id')]);
			$this.addClass('read');
		}
		
    }

    $scope.delete = function()
    {
		var checks = $('#mensajes').find('div.new').find('.mark:checked');
		var msj = "";
		(checks.length === 1) ? msj = "este registro" : msj = "estos " + checks.length + " registros";

		BootstrapDialog.confirm('¿Seguro que desea eliminar ' + msj +'?', function(resp){
			if(resp) {
				change_mark(get_object_id_news(checks), 'delete');
			}
		});

    }

    setInterval(function(){
		$scope.get_news();
	},60000);

}]);

