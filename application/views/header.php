<?php if(!is_array(@$arrayJs)) $arrayJs=array();?>
<?php if(!is_array(@$arrayCss)) $arrayCss=array(); ?>
<?php array_unshift($arrayJs,'jquery-1.10.2.js','bootstrap/bootstrap.min.js','datatables/jquery.dataTables.min.js','datatables/datatables.js', 'base.js', 'bootstrap/bootbox.min.js'); ?>
<?php array_unshift($arrayCss,'font-awesome/css/font-awesome.min.css', 'bootstrap.min.css','mybootstrap.css','main.css','datatables/datatables.css');?>
<?php $show_breadcrumbs = (isset($show_breadcrumbs))? $show_breadcrumbs: (bool)$this->session->userdata('usuario_appweb'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php if (isset($arrayCss)):?>
	<? foreach ($arrayCss as $dirCss):?>
	<link type="text/css" href=<?= base_url()."css/$dirCss" ?> rel="stylesheet" />
<? endforeach; ?>
<?php endif; ?>
<script>site_url='<?=site_url()?>';</script>
<?php if (isset($arrayJs)):?>
	<? foreach ($arrayJs as $dirJs):?>
	<script type="text/javascript" src=<?=base_url()."js/$dirJs" ?>></script>
<? endforeach; ?>
<?php endif; ?>


<title>Oficina Virtual | Dirección de Rentas Municipales - Alcaldía del Municipio Sucre · Estado Miranda</title>
</head>

<body class="edocuenta soloForm" <?php if (ENVIRONMENT == 'production'){ ?>ondragstart="return false" oncontextmenu="return false" onselectstart="return false" <?php }?> >
	<div id="wrapper">
		<div id="header">
			<?php $this->load->view('menu/top_menu') ?>
		</div><!--fin div header-->
		<div id="contenido">
			<div class="row">
				<?php if(isset($sidebar)): ?>
					<div id="sidebar" class="col-md-2">
						<?php $this->load->view($sidebar,@$data); ?>
					</div>
					<div id="main" class="col-md-10">
						<?php if ($show_breadcrumbs) echo create_breadcrumb(); ?>
				<?php else: ?>
				
				<div id="main" class="col-md-12">

				<?php endif; ?>

				<?php if ($this->session->userdata('usuario_appweb')): ?>
					
				<?php $this->load->view('gestion_usuario/modal_perfil', array('user' => $this->session->userdata('usuario_appweb'))); ?>

				<?php endif; ?>

				<?php $this->messages->get_messages(true) ?>


