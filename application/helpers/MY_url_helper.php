<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('create_breadcrumb')){
	function create_breadcrumb(){

		$tag_open_wrapper = '<ol class="breadcrumb">';
		$tag_close_wrapper = '</ol>';

		//Envoltorio del separador y separador
		$tag_open_sep = '';
		$tag_close_sep = '';
		$sep = '';

		//Envoltorio de la ultima miga
		$tag_open_lastcrumb = '';
		$tag_close_lastcrumb = '';

		//Clase de las migas anteriores a la ultima
		$class_link_crumb = '';

		// Etiquetas de cada miga
		$names = array(
			'oficina_principal' => 'Inicio',
			'edocuenta' => 'estado de cuenta',
			'planillas_pago' => 'planillas de pago',
			'generadas' => 'histórico',
			'cuentas' => 'nueva declaración',
			'historico' => 'histórico',
			'impuestos_confirmation' => 'confirmación',
			'tasas_confirmation' => 'confirmación',
			'gestion_usuario' => 'gestion de usuario',
			'modificar_perfil' => 'modificar',
			'procesos_administrativos' => 'procesos administrativos',
			'cedula_catastral' => 'cédula catastral',
			'pago_online' => 'pago en línea',
			'show_invoice_megasoft' => 'factura',
			'validar_anio' => 'validar año',
			'validar_mes' => 'validar mes'
		
		);

		$uris_nulas = array(
			'cargosedo',
			'oficina_right',
			'right2',
			'nuc',
			'index'
		);

		//LO QUE SE COLOQUE ACA HARA QUE NO SE MUESTRE EN EL BREAD CRUMB LO QUE SIGUE
		$last_crumb = array(
			'impuestos_confirmation',
			'pago_online'
		);

		$use_link = FALSE;

		$page_index = 'oficina_principal';

		$ci = &get_instance();
		#if ($ci->config->load('breadcrumb'))
			#extract($ci->config->item('breadcrumb')); //SOBREESCRIBIR VARIABLES DE ARCHIVO DE CONFIGURACION

		$num_link = $ci->uri->total_segments();
		$link = $tag_open_wrapper;

		if ($ci->uri->segment(1) != $page_index){ //ESCRIBIR INICIO
			$link_name = (isset($names[$page_index])) ? $names[$page_index] : $page_index;
			$link.='<li><a class="'.$class_link_crumb.'" href="'.site_url($page_index).'">'.$link_name.'</a></li>';
		}
		for ($i = 1; $i <= $num_link; $i++){
			$prep_link = "";
			if (in_array($ci->uri->segment($i),$uris_nulas)) continue; //NO ESCRIBE LINKS DE URIS_NULAS
			for($j=1; $j<=$i;$j++) $prep_link .= $ci->uri->slash_segment($j);
			$link_name = (isset($names[$ci->uri->segment($i)])) ? $names[$ci->uri->segment($i)] : $ci->uri->segment($i);
			if ($i < $num_link){ //SI NO ES EL ULTIMO SEGMENTO
				if ($use_link || $ci->uri->segment($i) == $page_index)
					$link.='<li><a class="'.$class_link_crumb.'" href="'.site_url($prep_link).'">'.$link_name.'</a></li>';
				else
					$link.="<li>" . $tag_open_lastcrumb.$link_name.$tag_close_lastcrumb. "</li>";
			}else{
				$link.="<li>".$tag_open_lastcrumb.$link_name.$tag_close_lastcrumb."</li>";
			}
			if (in_array($ci->uri->segment($i),$last_crumb)) break; //NO CREAR MAS CRUMB
		}
		$link .= $tag_close_wrapper;
		#var_dump($link);
		return $link;
	}

}

function get_html_tax_information_condensed($tax_information_condensed, $sep = "\n")
{
	$html = "";

	if ($tax_information_condensed)
	{
		if (is_object($tax_information_condensed))
		{
			foreach ($tax_information_condensed as $name => $value)
			{
				$html.= ucfirst($name) . ": $value" . $sep;
			}
			return substr($html, 0, -1 * count($sep));
		}
		else
		{
			return $tax_information_condensed;
		}
		
	}

	return $html;
}

function proccess_tax_information_condensed($tax)
{
    if ($tax->tax_information_condensed)
    {
        $tax->tax_information_condensed = json_decode($tax->tax_information_condensed);
    }
    else
    {
        $tax->tax_information_condensed = strtolower($tax->name);
    }

    $tax->html_tax_information_condensed = get_html_tax_information_condensed($tax->tax_information_condensed);

    return $tax;
}

function getClientIP() {

	if (isset($_SERVER)) {

		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
			return $_SERVER["HTTP_X_FORWARDED_FOR"];

		if (isset($_SERVER["HTTP_CLIENT_IP"]))
			return $_SERVER["HTTP_CLIENT_IP"];

		return $_SERVER["REMOTE_ADDR"];
	}

	if ($ip = getenv('HTTP_X_FORWARDED_FOR'))
		return $ip;

	if ($ip = getenv('HTTP_CLIENT_IP'))
		return $ip;

	return getenv('REMOTE_ADDR');
}

function objectToArray($object)
{
	$r = array();
	if ($object)
        foreach ($object as $i => $result)
        	$r[$i] = $result;
    return $r;
}



/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */