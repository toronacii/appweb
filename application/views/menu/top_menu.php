<?php
	$user=$this->session->userdata('taxpayer');
	$info_user=$this->session->userdata('usuario_appweb');
	$tax_types = $this->session->userdata('tax_types');
?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
<div class="col-md-2 col-xs-2" style="padding-left:0; padding-right:0; background-color:white">
	<div class="navbar-header"><img style="border-color: #e76a0b; width:100%; height:51px"src="<?php echo base_url('css/img/alcaldia-rentas.png') ?>"></div>
	<!--<div class="navbar-header pull-right"><img style="border-color: #e76a0b;" src="<?php echo base_url('css/img/rentas.png') ?>"></div>-->
</div>

	<div class="navbar-header">
		<button type="button" data-toggle="collapse" data-target=".navbar-ex1-collapse" class="navbar-toggle <?php if (! $info_user) : ?> visible-sm visible-xs <?php endif; ?>">
			<span class="sr-only">Desplegar navegación</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?php echo site_url('oficina_principal') ?>">Oficina Virtual</a>
	</div>
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav visible-sm visible-xs">
			<li><a href="<?php echo site_url(); ?>">Inicio</a></li>
			<?php if ($info_user): ?>
				<li><a href="<?php echo site_url('oficina_principal/edocuenta'); ?>">Estados de cuenta</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Pago de impuestos <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo site_url('planillas_pago/tasas'); ?>">Planilla de tasas</a></li>
						<li><a href="<?php echo site_url('planillas_pago/impuestos'); ?>">Planilla de impuestos</a></li>
						<?php if ($this->session->userdata('total_taxes') >= 5): ?>
						<li><a href="<?php echo site_url('planillas_pago/unificada'); ?>">Planilla unificada</a></li>
						<?php endif; ?>
						<li><a href="<?php echo site_url('oficina_principal/generadas'); ?>">Histórico de planillas</a></li>
					</ul>
				</li>
				<?php if ($tax_types[1]->total): # ACTIVIDADES ECONOMICAS ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Declaraciones <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo site_url('declaraciones/cuentas'); ?>">Nueva</a></li>
						<li><a href="<?php echo site_url('declaraciones'); ?>">Anteriores</a></li>
					</ul>
				</li>
				<?php endif; ?>

				<?php if ($tax_types[1]->total || $tax_types[2]->total): # ACTIVIDADES ECONOMICAS O INMUEBLES?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Trámites <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<?php if ($tax_types[1]->total || $tax_types[2]->total): ?>
						<li><a href="<?php echo site_url('tramites/solvencias'); ?>">Solvencias</a></li>
						<?php if ($tax_types[2]->total) : ?>
	                    <li><a href="<?php echo site_url('tramites/cedula_catastral'); ?>">Cédula catastral</a></li>
	                    <?php endif; ?>
	                    <li><a href="<?php echo site_url('tramites/historico'); ?>">Histórico</a></li>
						<?php endif; ?>
					</ul>
				</li>
				<?php endif; ?>
				<li><a href="<?php echo site_url('tramites/procesos_administrativos'); ?>">Procesos administrativos</a></li>
			<?php endif; ?>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Publicidad <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo site_url('publicidad/calculadora'); ?>">Calculadora</a></li>
				</ul>
			</li>
			<?php if (! $info_user): ?>
				<li><a href="<?php echo site_url('eventual/express'); ?>">Planilla express</a></li>
				<li><a href="<?php echo site_url('eventual'); ?>">Contribuyente eventual</a></li>
			<?php endif; ?>
			<?php if ($info_user): ?>
				<li><a href="<?php echo site_url('fiscales'); ?>">Fiscales</a></li>
			<?php endif;  ?>
			</ul>
		<?php if ($info_user): ?>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><strong><?php echo $info_user->nombres." ".$info_user->apellidos?></strong> <b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#" data-toggle="modal" data-target="#modalPerfil">Datos de usuario</a></li>
						<li><a href="<?php echo site_url('principal/salir'); ?>">Cerrar sesión</a></li>
					</ul>
				</li>
			</ul>
		<?php endif; ?>
	</div>
</nav>