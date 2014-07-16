<?php  #if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Envoltorio de la miga
$config['breadcrumb']['tag_open_wrapper']= '<ol class="breadcrumb">';
$config['breadcrumb']['tag_close_wrapper']= '</ol>';

//Envoltorio del separador y separador
$config['breadcrumb']['tag_open_sep']= '';
$config['breadcrumb']['tag_close_sep']= '';
$config['breadcrumb']['sep']= '';

//Envoltorio de la ultima miga
$config['breadcrumb']['tag_open_lastcrumb']= '';
$config['breadcrumb']['tag_close_lastcrumb']= '';

//Clase de las migas anteriores a la ultima
$config['breadcrumb']['class_link_crumb']= '';

// Etiquetas de cada miga
$config['breadcrumb']['names'] = array(
	'oficina_principal' => 'Inicio',
	'edocuenta' => 'estado de cuenta',
	'planillas_pago' => 'planillas de pago',
	'generadas' => 'histórico',
	'cuentas' => 'nueva declaración',
	'historico' => 'histórico',
	'impuestos_confirmation' => 'confirmación',
	'tasas_confirmation' => 'confirmación'
	
);

$config['breadcrumb']['uris_nulas'] = array(
	'cargosedo',
	'oficina_right',
	'right2',
	'nuc',
	'index'
);

//LO QUE SE COLOQUE ACA HARA QUE NO SE MUESTRE EN EL BREAD CRUMB LO QUE SIGUE
$config['breadcrumb']['last_crumb'] = array(
	'impuestos_confirmation'
);

$config['breadcrumb']['use_link'] = FALSE;

$config['breadcrumb']['page_index'] = 'oficina_principal';