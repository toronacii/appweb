<div id="mailbox" ng-app="app" ng-controller="mainController">

	<div class="row">
		<div class="col-md-12">
			<!-- Split button -->
			<div class="btn-group">
				<button type="button" class="btn btn-default">
					<div class="checkbox" style="margin: 0;">
						<label>
							<input type="checkbox" class="all" ng-click="checkAll($event.target)" ng-model="checkAllButton">
						</label>
					</div>
				</button>
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span><span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu check" role="menu">
					<li><a href="#" id="readed" ng-click="checkType('read', $event)">Leidos</a></li>
					<li><a href="#" id="unreaded" ng-click="checkType('unread', $event)">Sin leer</a></li>
				</ul>
			</div>
			<a href="#" class="btn btn-default" title="Recargar" ng-click="get_news()">
				   <span class="glyphicon glyphicon-refresh" ng-class="{'fa-spin' : loading}"></span>&nbsp;&nbsp;&nbsp;
			</a>
			<a href="#" class="actions" ng-class="{'hide' : ! check}" ng-click="delete()">
				<button class="btn btn-danger" title="Eliminar" id="delete">
					&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;&nbsp;
				</button>
			</a>
			<div class="btn-group" id="btn-mas">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Mas
					<span class="caret"></span><span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="mas dropdown-menu" role="menu">
					<li ng-class="{hide : ! more.readed}" ng-click="changeType('read')"><a href="#">Marcar como leído</a></li>
					<li ng-class="{hide : ! more.unreaded}" ng-click="changeType('unread')"><a href="#">Marcar como no leído</a></li>
					<li ng-class="{hide : ! more.all_readed}" ng-click="changeType('read')"><a href="#">Marcar todo como leído</a></li>
					<li ng-class="{hide : ! more.all_unreaded}" ng-click="changeType('unread')"><a href="#">Marcar todo como no leído</a></li>
				</ul>
			</div>

			<input class="form-control inline" type="text" placeholder="Buscar" ng-model="search" ng-keyup="checkParent()">

			<div class="pull-right">
				<span class="text-muted"><b>{{ pagination.page * pagination.perPage + 1 }}</b>–<b>{{ (pagination.page + 1 === pagination.numPages && (pagination.perPage * (pagination.page + 1)) > pagination.totalElements) ? filtered.length : pagination.perPage * (pagination.page + 1) }}</b> de <b>{{ pagination.totalElements }}</b></span>
				<div class="btn-group btn-group-sm">
					<button type="button" class="btn btn-default" ng-click="pagination.prevPage()">
						<span class="glyphicon glyphicon-chevron-left"></span>
					</button>
					<button type="button" class="btn btn-default" ng-click="pagination.nextPage(); ">
						<span class="glyphicon glyphicon-chevron-right"></span>
					</button>
				</div>
			</div>
		</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-12">
			<!-- Nav tabs -->
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#mensajes" data-toggle="tab">
						<span class="glyphicon glyphicon-inbox"></span>Mensajes
					</a>
				</li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane fade in active" id="mensajes">
					<div class="list-group">
						<input type="hidden" id="id_taxpayer" value="<?php echo $this->session->userdata('taxpayer')->id_taxpayer ?>">
					
						<div ng-repeat="new in filtered | startFrom: pagination.page * pagination.perPage | limitTo: pagination.perPage | filter:search" on-finish-render-filters>
							
							<div class="new list-group-item" ng-class="{'read' : new.read_date}" data-content="{{ new.message }}" data-id="{{ new.id }}" ng-click="showMessage($event.target)">
								<div class="checkbox" style="float:left">
									<label><input type="checkbox" class="mark" data-id="{{ new.id }}" ng-model="new.checked" ng-click="checkParent($event)"></label>
								</div>
								<div class="col-md-2 col-sm-2 overflow">
									<span class="title" title="{{ new.title }}">{{ new.title }}</span>
								</div>
								<div class="col-md-8 col-sm-8 overflow">
									<span class="label-type label label-{{ new.type }}" data-type="{{ new.type }}">label</span>
									<span class="excerpt">{{ new.message_strip_tags }}</span>
								</div>
								<span class="badge">{{ new.created }}</span>
								<div class="clearfix"></div>
							</div>

						</div>

						<div class="list-group-item text-center" ng-class="{'hide' : news.length}">
							No posee mensajes en su buzón
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
