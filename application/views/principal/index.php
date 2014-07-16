<div id="mailbox">

<div class="row">
	<div class="col-md-12">
		<!-- Split button -->
		<div class="btn-group">
			<button type="button" class="btn btn-default">
				<div class="checkbox" style="margin: 0;">
					<label>
						<input type="checkbox" class="all">
					</label>
				</div>
			</button>
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span><span class="sr-only">Toggle Dropdown</span>
			</button>
			<ul class="dropdown-menu check" role="menu">
				<li><a href="#" id="readed">Leidos</a></li>
				<li><a href="#" id="unreaded">Sin leer</a></li>
			</ul>
		</div>
		<a href="#" class="btn btn-default" title="Recargar" onclick="location.reload()">
			   <span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;&nbsp;
		</a>
		<a href="#" class="actions hide">
			<button class="btn btn-danger" title="Eliminar" id="delete">
				&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;&nbsp;
			</button>
		</a>
		<div class="btn-group" id="btn-mas">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Mas
				<span class="caret"></span><span class="sr-only">Toggle Dropdown</span>
			</button>
			<ul class="mas dropdown-menu" role="menu">
				<li class="hide mark_read" data-type="mark"><a href="#">Marcar como leído</a></li>
				<li class="hide mark_unread" data-type="unmark"><a href="#">Marcar como no leído</a></li>
				<li class="hide mark_all_read" data-type="mark"><a href="#">Marcar todo como leído</a></li>
				<li class="hide mark_all_unread" data-type="unmark"><a href="#">Marcar todo como no leído</a></li>
			</ul>
		</div>

		<input class="form-control inline" type="text" name="find" placeholder="Buscar">

		<div class="pull-right">
			<span class="text-muted"><b>1</b>–<b>50</b> of <b>277</b></span>
			<div class="btn-group btn-group-sm">
				<button type="button" class="btn btn-default">
					<span class="glyphicon glyphicon-chevron-left"></span>
				</button>
				<button type="button" class="btn btn-default">
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
			<!--
			<li>
				<a href="#profile" data-toggle="tab">
					<span class="glyphicon glyphicon-user"></span>Social
				</a>
			</li>
			-->
		</ul>
		<!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane fade in active" id="mensajes">
				<div class="list-group">
					<input type="hidden" id="id_taxpayer" value="<?php echo $this->session->userdata('taxpayer')->id_taxpayer ?>">

					<?php foreach ($news as $new): ?>

					<div class="new list-group-item <?php echo ($new->read_date) ? "read" : "" ?>" data-content="#<?php echo "id_{$new->id}" ?>">

						<div class="checkbox" style="float:left">
							<label><input type="checkbox" class="mark" data-id="<?php echo $new->id ?>"></label>
						</div>
						<div class="col-md-2 col-sm-2 overflow">
							<span class="title" title="<?php echo $new->title ?>"><?php echo $new->title ?></span>
						</div>
						<div class="col-md-8 col-sm-8 overflow">
							<span class="label-type label label-<?php echo $new->type ?>" data-type="<?php echo $new->type ?>"><?php echo "label" ?></span>
							<span class="excerpt"><?php echo strip_tags($new->message) ?></span>
						</div>
						
						<span class="badge"><?php echo ($new->created == date('Y-m-d')) ? "HOY" : date('d/m/Y', strtotime($new->created)) ?></span>
						<div class="clearfix"></div>
					</div>

					<div class="hide content" id="<?php echo "id_{$new->id}" ?>"><?php echo $new->message ?></div>

					<?php endforeach; ?>

					<div class="list-group-item text-center hide" id="empty-inbox">
						No posee mensajes en su buzón
					</div>
				</div>
			</div>
			<!--
			<div class="tab-pane fade in" id="messages">...</div>
			<div class="tab-pane fade in" id="settings">This tab is empty.</div>
			-->
		</div>
	</div>
</div>

</div>