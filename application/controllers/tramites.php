<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tramites extends MY_Controller {

	public $taxes;

	function __construct() {
        parent::__construct();
        if (!$this->session->userdata('usuario_appweb'))
            redirect(base_url());

        $this->taxes = $this->session->userdata('taxes');
        $this->load->model('api_model', 'tramites');

    }

    public function solvencias()
    {
    	$header['sidebar'] = 'menu/oficina_menu';
    	$header['arrayJs'] = array('funciones_tramites.js','funciones_retiro.js');
    	$this->load->view('header', $header);

    	$data['cuentas'] = $this->taxes;
    	$data['tax_types'] = $this->session->userdata('tax_types');
		$data['taxpayer'] = $this->session->userdata('taxpayer');

    	#var_dump($_POST);

        if (isset($_POST['id_tax']))
        {
            $taxes = (array)$this->taxes;
            $id_tax = $_POST['id_tax'];

            $id_request = $this->tramites->insert_request_solvencia($_POST['id_tax']);

            #dd($id_request, $this->tramites);

            switch ($id_request) {
                #TIENE UN CONVENIO DE PAGO
                case -1:
                    $this->messages->add_message("Usted está en proceso de convenio de pago, por ello no puede realizar este trámite");
                    break;
                #DEBE ALGÚN TRIMESTRE
                case -2:
                    if ($taxes[$id_tax]->id_tax_type == 1) #ACTIVIDADES ECONOMICAS
                    {
                        $this->messages->add_message("Usted debe algún aforo mensual exigible, por ello no puede realizar este trámite");
                    }
                    else #INMUEBLES URBANOS
                    {
                        $this->messages->add_message("Usted debe algún trimestre exigible, por ello no puede realizar este trámite");
                    }
                    break;
                #NO ESTÁ SOLVENTE A LA DEUDA EXIGIBLE
                case 0:
                    $this->messages->add_message("Usted no está solvente a la deuda exigible, por ello no puede realizar este trámite");
                    break;
                #SI SE INSTANCIÓ EL TRÁMITE
                default:
                    $this->messages->add_message("Trámite solicitado con éxito", "success");
                    redirect(site_url("tramites/imprimir/$id_request"));
            }
        }

    	$this->load->view('tramites/solvencias_view', $data);

    	$this->load->view('footer');
    }

    public function cedula_catastral()
    {
        $header['sidebar'] = 'menu/oficina_menu';
        $header['arrayJs'] = array('funciones_tramites.js');
        $this->load->view('header', $header);

        $data['tax_types'] = $this->session->userdata('tax_types');
        $data['taxpayer'] = $this->session->userdata('taxpayer');

        $data['catastro'] = $this->tramites->get_procedimiento_catastro($data['taxpayer']->id_taxpayer);

        $this->load->view('tramites/cedula_catastral', $data);

        $this->load->view('footer');
    }

    public function imprimir($id_request)
    {
        $this->session->set_userdata('id_request', $id_request);
        redirect(site_url('generar_planilla/imprime_solvencia'));
    }

    public function imprimir_retiro($id_request)
    {
        $this->session->set_userdata('id_request', $id_request);
        redirect(site_url('generar_planilla/imprime_retiro'));
    }

    public function ajax_get_table_validations($id_tax, $type_tramite = 1)
    {

        $data['id_tax_type'] = $this->taxes[$id_tax]->id_tax_type;
        $data['id_tax'] = $id_tax;

        $data['passed'] = true;

        #PARA OTROS TIPOS DE TRÁMITES
        switch ($type_tramite)
        {
            #SOLVENCIAS
            case 1:

                $data['passed'] &= $data['tasa'] = $this->tramites->have_tasa_paid($id_tax);
                $data['passed'] &= $data['estado_cuenta'] = $this->tramites->esta_solvente($id_tax);

                if ($data['id_tax_type'] == 1) #ACTIVIDADES ECONOMICAS
                {
                    $data['declaraciones'] = implode('<br>', $this->tramites->declaraciones_anteriores($id_tax));
                    $data['passed'] &= count($data['declaraciones']) == 0;
                }
                else #INMUEBLES URBANOS
                {
                    $data['passed'] &= $data['cedula_catastral'] = $this->tramites->cadastral_number_actualized($id_tax);
                }

                $this->load->view('tramites/partials/table_solvencia_validation', $data);

            break;
        }

        #var_dump($data, (bool)$data['declaraciones']);

        //echo json_encode($data);
    }


/// RETIRO DE ACTIVIDADES ECONOMICOS

    public function ajax_get_table_validations_retiro($id_tax)
    {

        $data['id_tax_type'] = $this->taxes[$id_tax]->id_tax_type;
        $data['id_tax'] = $id_tax;
        $data['taxpayer'] = $this->session->userdata('taxpayer');
        $data['passed'] = true;
        $data['passed'] &= $data['tiene_tasa'] = $this->tramites->have_tasa_paid($id_tax,'retiro');
        $data['passed'] &= $data['esta_solvente'] = $this->tramites->esta_solvente($id_tax);
        $data['passed'] &= $data['no_procedimientos'] =  ( 
            $this->tramites->get_procedimiento_auditoria_retiro($id_tax) || 
            $this->tramites->get_procedimiento_fiscalizacion_retiro($id_tax)
        );
        #dd($data);
        $data['errores_declaraciones'] = implode('<br>', $this->tramites->declaraciones_anteriores($id_tax));
        $data['passed'] &= count($data['errores_declaraciones']) == 0;
        
        $data['passed'] &= $data['tiene_mensual'] = $this->tramites->have_statement($id_tax);
        $data['passed'] &= $data['tiene_cese'] = $this->tramites->have_statement($id_tax, true);

        #dd($data);
        $this->load->view('tramites/partials/table_retiro_validation', $data);

        #var_dump($data, (bool)$data['declaraciones']);

        //echo json_encode($data);
    }

    // INSERTAR RETIRO
    public function crear_retiro()
    {
        $id_taxpayer = $this->session->userdata('taxpayer');
        $id_tax = $_POST['id_tax'];
        $id_request = $this->tramites->post_retiro($id_tax, $id_taxpayer->id_taxpayer);
      
        #dd($_POST['id_tax']);
       // redirect('controller/action/params')

        $this->session->set_userdata('id_request', $id_request);
        redirect(site_url('generar_planilla/imprime_retiro'));

    }

    ////FIN 

    //Retido de Actividades Economicas
    public function retiro()
    {
        $header['sidebar'] = 'menu/oficina_menu';
        $header['arrayJs'] = array('funciones_retiro.js');
        $this->load->view('header', $header);

        $data['cuentas'] = $this->taxes;
        $data['tax_types'] = $this->session->userdata('tax_types');
        $data['taxpayer'] = $this->session->userdata('taxpayer');

        #var_dump($_POST);

        if (isset($_POST['id_tax']))
        {
            $taxes = (array)$this->taxes;
            $id_tax = $_POST['id_tax'];

            $id_request = $this->tramites->insert_request_solvencia($_POST['id_tax']);

            #dd($id_request, $this->tramites);

            switch ($id_request) {
                #TIENE UN CONVENIO DE PAGO
                case -1:
                    $this->messages->add_message("Usted está en proceso de convenio de pago, por ello no puede realizar este trámite");
                    break;
                #DEBE ALGÚN TRIMESTRE
                case -2:
                    if ($taxes[$id_tax]->id_tax_type == 1) #ACTIVIDADES ECONOMICAS
                    {
                        $this->messages->add_message("Usted debe algún aforo mensual exigible, por ello no puede realizar este trámite");
                    }
                    else #INMUEBLES URBANOS
                    {
                        $this->messages->add_message("Usted debe algún trimestre exigible, por ello no puede realizar este trámite");
                    }
                    break;
                #NO ESTÁ SOLVENTE A LA DEUDA EXIGIBLE
                case 0:
                    $this->messages->add_message("Usted no está solvente a la deuda exigible, por ello no puede realizar este trámite");
                    break;
                #SI SE INSTANCIÓ EL TRÁMITE
                default:
                    $this->messages->add_message("Trámite solicitado con éxito", "success");
                    redirect(site_url("tramites/imprimir/$id_request"));
            }
        }

        $this->load->view('tramites/retiro_view', $data);

        $this->load->view('footer');
    }


    public function historico()
    {
        $header['sidebar'] = 'menu/oficina_menu';
        $header['arrayJs'] = array();
        $this->load->view('header', $header);

        $data['taxpayer'] = $this->session->userdata('taxpayer');
        $data['tax_types'] = $this->session->userdata('tax_types');
        $data['tramites'] = $this->tramites->get_solvencias_taxpayer($data['taxpayer']->id_taxpayer);
        $data['tramites_retiro'] = $this->tramites->get_retiro_taxpayer($data['taxpayer']->id_taxpayer);
        #dd($data, $this->tramites);

        $this->load->view('tramites/historico', $data);

        $this->load->view('footer');
    }

    public function procesos_administrativos()
    {
        $header['sidebar'] = 'menu/oficina_menu';
        $header['arrayJs'] = array();
        $this->load->view('header', $header);

        $data['taxpayer'] = $this->session->userdata('taxpayer');
        $data['tax_types'] = $this->session->userdata('tax_types');
        $data['procedimientos'] = array(
            'auditoria' => $this->tramites->get_procedimiento_auditoria($data['taxpayer']->id_taxpayer),
            'fiscalizacion' => $this->tramites->get_procedimiento_fiscalizacion($data['taxpayer']->id_taxpayer)
        );

        #d($data, $this->tramites);

        $this->load->view('tramites/procesos_administrativos', $data);

        $this->load->view('footer');
    }  

    public function set_session_statement ($id_tax, $closing = false)
    {
        $tax_account_number = $_SESSION['taxes'][$id_tax]->tax_account_number;

        $_SESSION["sttm_tax"] = [
            'sttm' => [date("m"), date("Y")],
            'tax' => [
                $tax_account_number => (object) [
                    'id_tax' => $id_tax,
                    'id_sttm_form' => 0
                ]
            ]
        ];

        if ($closing) {
           $_SESSION["sttm_tax"]['sttm'][2] = 'CLOSING'; 
        }
             

        redirect("declaraciones/crear/$tax_account_number");
    }




}