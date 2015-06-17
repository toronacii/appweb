<script>statement.factory('StatementData', [function(){return <?php echo json_encode($statementData) ?>}]);</script>
<script>statement.factory('TaxClassifierSpecialized', [function(){return <?php echo json_encode($specialized) ?>}]);</script>

<div ng-app="statement" ng-controller="statementCtrl" ng-cloak>

	<form name="declaraciones" id="fDeclaraciones" method="post" action="<?php echo site_url('declaraciones/declarar'); ?>">
	    
	<div class="row form-group">
	    <ul class="nav nav-pills nav-justified thumbnail setup-panel pasos">
	    	<li ng-repeat="step in steps" ng-class="{'active': $index === 0, 'disabled': $index > 0}">
		    	<a href="#paso-{{ $index + 1 }}">
		            <h4 class="list-group-item-heading">Paso <span ng-bind="$index + 1"></span></h4>
		            <p class="list-group-item-text" ng-bind="step"></p>
		        </a>
		    </li>
	    </ul>
	</div>