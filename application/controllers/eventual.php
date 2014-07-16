<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Eventual extends MY_Controller {

	public $cuenta;

    function __construct() {
        parent::__construct();
        if ($this->session->userdata('usuario_appweb'))
            redirect(site_url('oficina_principal'));
        $this->session->set_userdata('eventual', true);
        $header['arrayJs'] = array('round.js','number_format.js','funciones_planillas_pago.js');
        $header['sidebar'] = 'menu/oficina_menu';
        $this->load->view('header', $header);
    }

    public function index()
    {
    	$this->load->model('api_model', 'planillas');
	    $data['tasas'] = $this->convert_fee_tax($this->planillas->tipos_tasas(array(1,2,3,4,5,6,7,8), true));

        if (isset($_POST) && !empty($_POST))
        {
        	$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
	        $this->form_validation->set_rules('razon_social', 'Razón social', 'required|trim');
	        $this->form_validation->set_rules('rif', 'C.I. / RIF', 'required|trim|numeric');
	        $this->form_validation->set_rules('direccion', 'Direccion', 'required|trim');
	        $this->form_validation->set_rules('tipo_doc', '', 'required|trim');
	        $this->form_validation->set_rules('id_tasa', 'Tasas', 'required|trim');

	        if ($this->form_validation->run())
	        {
	        	$tax = new stdClass();
	        	$tax->id_tax = 'NULL';
	        	$tasa = new stdClass();
	        	$tasa->id = $_POST['id_tasa'];
	        	$this->session->set_userdata('imprime_tasa', array('tax' => $tax, 'tasa' => $tasa));
	        	$this->session->set_userdata('metadata', $_POST);
	        	redirect('planillas_pago/generar_planilla_tasa');
	        }
        }
    	
        

        $this->load->view('eventual/eventual', $data);
    	$this->load->view('footer');
    }

    private function convert_fee_tax($fee_taxes)
    {
    	$fee_names = array(
    		1 => "Tasas para trámites de Actividades Económicas",
    		2 => "Tasas para trámites de Inmuebles Urbanos",
    		3 => "Tasas para trámites de Vehiculos",
    		4 => "Tasas para trámites de Publicidad",
    		5 => "Tasas para trámites en Catastro Municipal",
    		6 => "Tasas para trámites de Ingeniería Municipal",
    		7 => "Tasas para trámites de Registro Y Notaria",
    		8 => "Otras Tasas Administrativas"
    	);

    	foreach ($fee_taxes as $fee_tax)
    	{
    		$return[$fee_names[$fee_tax->id_tax_type]][] = $fee_tax;
    	}
    	return $return;

    }

    public function express() {
		
		$data = array();
        if (isset($_POST) && !empty($_POST))
        {
        	$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
	        $this->form_validation->set_rules('cuenta', 'Número de cuenta', 'required|trim|numeric|exact_length[9]|callback_valid_cuenta');
	        $this->form_validation->set_rules('tipoplan', 'Tipo de trámite', 'required|trim');
	        
	        
	        if ($this->form_validation->run() !== FALSE) 
	        {
	        	$this->load->model('api_model', 'planillas');
	        	$cuenta = $this->cuenta;
	        	$data['id_tax'] = $cuenta->id_tax;

	        	switch ($_POST['tipoplan'])
	        	{
	        		case 'admin':
	        			unset($_SESSION['cargos_planilla']);
	        			$data['tasas'] = objectToArray($this->planillas->tipos_tasas(array($cuenta->id_tax_type)));
	        			$this->session->set_userdata('tipos_tasas', $data['tasas']);
	        		break;

	        		case 'impuesto':
	        			unset($_SESSION['tipos_tasas'], $_SESSION['imprime_tasa']);
	        			$cargos = objectToArray($this->planillas->get_cargos_taxpayer($cuenta->id_taxpayer, $cuenta->id_tax));
	        			
	        			#var_dump($cargos); exit;
	        			
	        			if ($cargos)
	        			{
	        				unset($_SESSION['tipos_tasas'], $_SESSION['imprime_tasa'], $_SESSION['cargos_planilla']);
	        				$this->session->set_userdata('cargos_planilla', $cargos);
	        				redirect("planillas_pago/impuestos_confirmation/{$cuenta->id_tax}");
	        			}
	        			$this->form_validation->add_error('cuenta', "No posee cargos pendientes por pagar, o por añadir a una planilla");

	        		break;

	        		case 'reimprimir':
	        			redirect("planillas_pago/generadas");
	        		break;
	        	}
	        		        	
	        	#redirect(site_url('eventual/impuestos'));
	        }
  
        }

        $this->load->view('eventual/express', $data);
        

        $this->load->view('footer');
    }

    public function valid_cuenta($str)
    {	
    	$this->load->model('api_model', 'gestion_usuario');
    	$cuenta = $this->gestion_usuario->tax($str);
    	#var_dump($cuenta, $this->gestion_usuario); exit;
    	if (! $cuenta)
    	{
    		$this->form_validation->set_message('valid_cuenta', 'Número de cuenta inexistente');
            return FALSE;
    	}



    	$this->load->model('api_model', 'principal');
    	$this->cuenta = $cuenta;
    	$this->session->set_userdata('taxpayer', $this->principal->taxpayer($cuenta->id_taxpayer));
    	
    	$tax_types[$cuenta->id_tax_type] = (object)array('name' => $cuenta->name);
    	$this->session->set_userdata('tax_types', $tax_types);
    	$this->session->set_userdata('taxes', array($cuenta->id_tax => $cuenta));

    	#var_dump($this->cuenta, 3);

    	

    	return TRUE;
    }

}