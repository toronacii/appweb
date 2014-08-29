<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Planillas_pago extends MY_Controller {

	public $taxes;

	public function __construct()
	{
		parent::__construct();

        if (! $this->session->userdata('usuario_appweb') && ! $this->session->userdata('eventual'))
            redirect(base_url());

        $this->load->model('api_model', 'planillas');

        $header['sidebar'] = 'menu/oficina_menu';
		$header['arrayJs'] = array('round.js','number_format.js','funciones_planillas_pago.js');
        $this->load->view('header', $header);

        $this->taxes = $this->session->userdata('taxes');
	}

	public function tasas()
	{
		$data['cuentas'] = $this->taxes;
		$data['tax_types'] = $this->session->userdata('tax_types');
		$data['tipos_tasas'] = $this->session->userdata('tipos_tasas');
		$data['taxpayer'] = $this->session->userdata('taxpayer');

		#d($_SESSION);

 		$this->load->view('planillas_pago/tasas_view', $data);

		$this->load->view('footer');

	}

	public function tasas_confirmation()
	{
		#d($_SESSION);
		#var_dump($_SESSION);exit;
		#var_dump($_POST, $this->session->userdata('tipos_tasas'));
		extract($_POST);

		$page = ($this->session->userdata('eventual')) ? 'eventual/express' : 'planillas_pago/tasas';

		if (! ($id_tax && $id_tasa) ||
			! preg_match('/^[\d]+$/', $id_tax) ||
			! preg_match('/^[\d]+$/', $id_tasa) ||
			! ($tipos_tasas = $this->session->userdata('tipos_tasas'))) {

			#CONTRIBUYENTE EVENTUAL
			$this->session->unset_userdata('imprime_tasa');
			redirect($page);
		}

		$data['tax'] = $this->taxes[$id_tax];
		$data['tax_types'] = $this->session->userdata('tax_types');
		$data['taxpayer'] = $this->session->userdata('taxpayer');
		$data['tasa'] = $tipos_tasas[$id_tasa];

		#var_dump($data, $this->taxes);


		$this->session->set_userdata('imprime_tasa', array('tax' => $data['tax'], 'tasa' => $data['tasa']));

		#d($_SESSION);

		$this->load->view('planillas_pago/tasas_confirmation_view', $data);

		$this->load->view('footer');

	}

	public function generar_planilla_tasa(){

		#dd($_SESSION, $this->session->userdata('imprime_tasa'));

		if (! $info_tasa = $this->session->userdata('imprime_tasa')){
			redirect(site_url('planillas_pago/tasas'));
		}

		$this->session->unset_userdata('imprime_tasa');

		#var_dump($this->planillas->generar_planilla_tasa($info_tasa['tax']->id_tax, $info_tasa['tasa']->id), $this->planillas); exit;

		if ($id_invoice = $this->planillas->generar_planilla_tasa($info_tasa['tax']->id_tax, $info_tasa['tasa']->id)){

			$this->session->set_userdata('id_invoice', $id_invoice);

			if ($info_tasa['tax']->id_tax == 'NULL' || @$_POST['action'] == 'imprimir'){

				redirect(site_url('generar_planilla/imprime_planilla'));

			}

			#PAGO EN LINEA
			if (@$_POST['action'] == 'pagar')
			{
				redirect(site_url("planillas_pago/pago_online/$id_invoice"));
			}

		}else{

			#dd($id_invoice, $this->planillas, $info_tasa);

			$this->messages->add_message("Ha ocurrido un error al insertar la planilla");
			#redirect(site_url($page));
		}

	}

	public function impuestos(){

		$data['cuentas'] = $this->taxes;
		$data['tax_types'] = $this->session->userdata('tax_types');
		$data['taxpayer'] = $this->session->userdata('taxpayer');

		$data['cargos'] = objectToArray($this->planillas->get_cargos_taxpayer($data['taxpayer']->id_taxpayer));

		#var_dump($this->planillas);

		$this->session->set_userdata('cargos_planilla', $data['cargos']);

		$this->session->unset_userdata('imprime_tasa');

		#var_dump($data['cargos']);

 		$this->load->view('planillas_pago/impuestos_view', $data);

		$this->load->view('footer');
	}

	public function impuestos_confirmation($id_tax){

		$cargos_planilla = $this->session->userdata('cargos_planilla');

		#var_dump($cargos_planilla); exit;

		#CONTRIBUYENTE EVENTUAL
		$page = ($this->session->userdata('eventual')) ? 'eventual/express' : 'planillas_pago/impuestos';

		if (! ($id_tax)
			|| ! preg_match('/^[\d]+$/', $id_tax)
			|| ! $cargos_planilla
			|| ! isset($cargos_planilla[$id_tax])) {

			redirect($page);
		}

		#var_dump($data);

		$data['tax'] = $this->taxes[$id_tax];
		$data['tax_types'] = $this->session->userdata('tax_types');
		$data['taxpayer'] = $this->session->userdata('taxpayer');
		$data['tax_types'] = $this->session->userdata('tax_types');

		$data['cargos'] = $cargos_planilla[$id_tax];

		#var_dump($data['cargos']);

		$this->load->view('planillas_pago/impuestos_confirmation_view', $data);

		$this->load->view('footer');

	}

	public function generar_planilla_impuesto(){

		#CONTRIBUYENTE EVENTUAL
		$page = ($this->session->userdata('eventual')) ? 'eventual/express' : 'planillas_pago/impuestos';

		if (! isset($_POST['id_tax']) || ! isset($_POST['cargos'])){
			redirect(site_url($page));
		}

		$ids_transaction = '{' . implode(',',array_keys($_POST['cargos'])) . '}';

		#var_dump($this->planillas->generar_planilla_impuesto($_POST['id_tax'], $ids_transaction), $this->planillas); exit;

		if ($id_invoice = $this->planillas->generar_planilla_impuesto($_POST['id_tax'], $ids_transaction)){

			$this->session->set_userdata('id_invoice', $id_invoice);

			if ($_POST['action'] == 'imprimir'){

				redirect(site_url('generar_planilla/imprime_planilla'));

			}

			#PAGO EN LINEA
			if ($_POST['action'] == 'pagar')
			{
				redirect(site_url("planillas_pago/pago_online/$id_invoice"));
			}


		}else{
			$this->messages->add_message("Ha ocurrido un error al insertar la planilla");
			redirect(site_url($page));
		}

	}

	public function unificada(){

		$data['cuentas'] = $this->taxes;
		$data['tax_types'] = $this->session->userdata('tax_types');
		$data['taxpayer'] = $this->session->userdata('taxpayer');

		$data['cargos'] = objectToArray($this->planillas->cuentas_usuario_unificada($data['taxpayer']->id_taxpayer));

		$this->session->set_userdata('cargos_planilla_unificada', $data['cargos']);

		#var_dump($data['cargos'][102803], $this->planillas);

 		$this->load->view('planillas_pago/unificada_view', $data);

		$this->load->view('footer');
	}

	public function generar_planilla_unificada(){

		# var_dump($_POST);

		if (! isset($_POST['cuentas'])){
			redirect(site_url('planillas_pago/unificada'));
		}

		$id_taxes = '{';
        foreach ($_POST['cuentas'] as $id_tax => $type) {
            $id_taxes .= '{' . "$id_tax," . $type . '},';
        }

        $id_taxes = substr($id_taxes, 0, -1) . '}';
        $id_taxpayer = $this->session->userdata('taxpayer')->id_taxpayer;

        #var_dump($this->planillas->generar_planilla_unificada($id_taxpayer, $id_taxes), $this->planillas); exit;

		if ($id_invoice = $this->planillas->generar_planilla_unificada($id_taxpayer, $id_taxes)){

			$this->session->set_userdata('id_invoice', $id_invoice);

			if ($_POST['action'] == 'imprimir')
			{
				redirect(site_url('generar_planilla/unificada'));
			}

			#PAGO EN LINEA
			if ($_POST['action'] == 'pagar')
			{
				redirect(site_url("planillas_pago/pago_online/$id_invoice"), 'refresh');
			}

		}else{
			$this->messages->add_message("Ha ocurrido un error al insertar la planilla");
			redirect(site_url('planillas_pago/unificada'));
		}
	}

	public function generadas(){

		$data['taxpayer'] = $this->session->userdata('taxpayer');

		$id_tax = NULL;
		#CONTRIBUYENTE EVENTUAL
		if ($this->session->userdata('eventual'))
		{
			$data['tax'] = $this->taxes[array_keys($this->taxes)[0]];
			$id_tax = $data['tax']->id_tax;
		}

		#var_dump($id_tax); exit;

		$planillas = $this->planillas->get_planillas_pago($data['taxpayer']->id_taxpayer, $id_tax);

		#echo "<pre>"; print_r($this->planillas->errors->message); var_dump($planillas, $this->planillas); exit;

		foreach ($planillas as $planilla)
		{
			$index = ($planilla->status == 'pagada') ? 'pagadas' : 'no_pagadas';

			$data['planillas'][$index][$planilla->id] = $planilla;

			$session_planillas[] = $planilla->id;
		}

		$this->session->set_userdata('planillas_imprimir', $session_planillas);

		$this->load->view('planillas_pago/planillas_view', $data);

		$this->load->view('footer');
	}

	public function delete($id_invoice)
	{
		if (! ($id_invoice) || ! preg_match('/^[\d]+$/', $id_invoice))
		{
			redirect(site_url('planillas_pago/generadas'));
		}

		#var_dump($this->planillas->delete_invoice($id_invoice), $this->planillas); exit;

		if ($this->planillas->delete_invoice($id_invoice))
			$this->messages->add_message("Planilla eliminada satisfactoriamente", 'success');
		else
			$this->messages->add_message("Error al eliminar la planilla");

		redirect(site_url('planillas_pago/generadas'));

		#var_dump($this->planillas);

	}

	public function pago_online($id_invoice)
	{
		if (! ($id_invoice) || ! preg_match('/^[\d]+$/', $id_invoice))
		{
			redirect(site_url());
		}

		$data = $this->planillas->get_data_invoice($id_invoice);

		#dd($this->planillas, $data);

		$post = array(
			'formAppWeb.email'  => $this->session->userdata('usuario_appweb')->email,
			'formAppWeb.pagina' => base_url()
		);

		foreach ($data AS $prop => $value)
		{
			if ($prop == 'total')
			{
				$value = number_format($value, 2, '.', '');
			}
			$post["formAppWeb.$prop"] = $value;
		}

		$control = json_decode($this->curl->simple_post(PAGO_ONLINE, $post));

		#dd($post, $this->planillas, $control);

		if (! isset($control->control))
		{
			echo (isset($control->error)) ? $control->error : "Ha ocurrido un error, intente de nuevo mas tarde";
		}
		else
		{
			redirect(BANESCO_ONLINE . $control->control);
		}

	}

}