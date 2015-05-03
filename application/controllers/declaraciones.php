<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Declaraciones extends MY_Controller {

    public $id_taxpayer;
    public $messages;
    public $sttm_properties;

    public function __construct() {
        parent::__construct();

        if (! $this->id_taxpayer = $this->session->userdata('taxpayer')->id_taxpayer)
            redirect(base_url());
        $this->load->model('api_model','declaraciones');
        $this->load->library("MessageSession");
        $this->messages = new MessageSession();
        $this->load->library("Statement");
        $this->statement = new Statement();
    }

    public function index()
    {
        redirect(site_url('declaraciones/historico'));
    }

    public function cuentas(){
        $header['arrayCss'] = array('declaraciones.css','sprites/32.css');
        $header['arrayJs'] = array(
            'number_format.js',
            'round.js',
            'validacionesToro.js',
            'funciones_declaraciones.js'
        );
        $header['sidebar'] = 'menu/oficina_menu';
        $this->load->view('header',$header);

        $this->statement->get_select_statement();

        $data['select'] = $this->statement->get_select_statement();

        $params = explode('_', $data['select'][0]);

        #d($data, $params);

        unset($_SESSION['sttm_tax']);

        if (isset($_POST) && !empty($_POST)){
            $params = explode('_', $_POST['statement_filter']);
            $this->load->library('form_validation');
            $this->form_validation->set_rules('statement_filter', 'Filtro', 'trim');
            $this->form_validation->run();
        }

        $_SESSION['sttm_tax']['sttm'] = $params;

        #var_dump($_SESSION['sttm_tax']);
        #DECLARACIONES SIMPLES
        if ($params[0] == 'TRUE')
        {
            $params[0] = 'NULL';
        }
        
        $data['declaraciones'] = $this->statement->order_errors_declare_taxpayer_monthly($this->declaraciones->get_errors_declare_monthly($this->id_taxpayer, 'TRUE', $params[1], $params[0])); 
        #d($this->id_taxpayer, 'TRUE', $params[1], $params[0]);
        #d($data['declaraciones'], $params, $this->declaraciones);

        $this->load->view('declaraciones/cuentas_view', $data);
        $this->load->view('footer');
    }

    public function historico() {
        #echo $this->id_taxpayer;
        $data['declaraciones'] = objectToArray($this->declaraciones->get_declaraciones($this->id_taxpayer));
        $data['declaraciones']['accounts'] =  objectToArray($data['declaraciones']['accounts']);

        $data['taxpayer'] = $this->session->userdata('taxpayer');
        $data['tax_types'] = $this->session->userdata('tax_types');

        #var_dump($data['declaraciones'], $this->declaraciones); exit;
        $header['arrayCss'] = array('declaraciones.css', 'sprites/32.css');
        $header['arrayJs'] = array('funciones_declaraciones.js');
        $header['sidebar'] = 'menu/oficina_menu';
        $this->load->view('header',$header);
        $this->load->view('declaraciones/anteriores_view',$data);
        $this->load->view('footer');
    }

    public function detalleStatement($id_statement)
    {
        $data['declaracion'] = $this->declaraciones->get_declaracion($id_statement);
        $this->load->view('declaraciones/detalle_view',$data);
    }

    private function getSteps($sttm)
    {
        $steps = [
            "Actualice sus datos",
            "Ubicación geográfica",
            "Añadir actividades",
            "Especificar actividades",
            "Realizar declaración",
            "Finalizar proceso"
        ];

        if (! $this->statement->show_step_specified_activities($sttm))
        {
            unset($steps[3]);
            return array_values($steps);
        }

        return $steps;
    }

    public function crear($tax_account_number = NULL)
    {
        $sttm_tax = $this->session->userdata('sttm_tax');
        $this->sttm_properties = $this->statement->get_sttm_properties($sttm_tax);

        if ( !(
                $tax_account_number && #NO EXISTE ALGUN PARAMETRO
                is_numeric($tax_account_number) && #SON TIPOS DISTINTOS
                $sttm_tax && #NO EXISTE LA SESION DE LA CUENTAS
                array_key_exists($tax_account_number, $sttm_tax['tax']) #LA CUENTA ENVIADA NO PERTECNECE A LA DE LA SESION
            ))

            redirect (site_url ('declaraciones/cuentas'));

        $sttm_tax['tax'] = array($tax_account_number => $sttm_tax['tax'][$tax_account_number]);
        $sttm_tax['tax_account_number'] = $tax_account_number;
        $this->session->set_userdata('sttm_tax', $sttm_tax);

        $id_tax = $sttm_tax['tax'][$tax_account_number]->id_tax;
        $sttm_only = $sttm_tax['sttm'];
        $id_sttm_form = $sttm_tax['tax'][$tax_account_number]->id_sttm_form;

        $header['arrayCss'] = array('declaraciones.css');
        $header['arrayJs'] = array(
            'lodash.min.js',
            'angular/angular.min.js',
            'angular/declaraciones.js',
            'bootstrap/bootstrap-steps.js',
            'number_format.js',
            'round.js',
            'serialize.js',
            'validacionesToro.js'
            /*,
            
            ,
            'funciones_declaraciones_logica.js',
            
            ,
            'jqueryui/core-effects.js'
            */
        );
        $header['sidebar'] = 'menu/oficina_menu';
        $header['show_breadcrumbs'] = FALSE;
        $this->load->view('header', $header);

        $show_step_four = $this->statement->show_step_specified_activities($sttm_only);

        $this->load->view('declaraciones/pasos/pasos', [
            'statementData' => [
                'steps' => $this->getSteps($sttm_only),
                'sttm_properties' => $this->sttm_properties,
                'show_step_four' => $show_step_four,
                'taxpayer' => $this->declaraciones->datos_taxpayer($id_tax),
                'tax_unit' => $this->declaraciones->get_tax_unit($this->sttm_properties->fiscal_year),
                'activities' => $this->declaraciones->get_activities($this->sttm_properties->fiscal_year),
                'tax_activities' => ($id_sttm_form > 0) ?
                    $this->declaraciones->get_data_statement($id_sttm_form) : 
                    $this->declaraciones->tax_activities($id_tax, $this->sttm_properties->fiscal_year)
            ],
            'specialized' => ($show_step_four) ? $this->declaraciones->get_tax_classifier_specialized() : []
        ]);


        $this->load->view('declaraciones/pasos/paso1');
        $this->load->view('declaraciones/pasos/paso2');
        $this->load->view('declaraciones/pasos/paso3');
        
        if ($show_step_four)
        {
            $this->load->view('declaraciones/pasos/paso4');
        }


        /*
        

        

        #d($sttm_only);

        $paso1['showStepFour'] = 
        
        

        
        $header['arrayVarsJs'] = array(
            'GLOBAL_showStepFour' => (int)$paso1['showStepFour'],
            'GLOBAL_fiscal_year' => $this->sttm_properties->fiscal_year
        );
        $this->load->view('header', $header);

        #var_dump($sttm_tax);

        

        #PASOS

        

        #dd($this->getSteps($sttm_only), $this->sttm_properties->fiscal_year, $sttm_only);

        #PASO 1
        $paso1['datos_contribuyente'] = 

        #var_dump($paso1['datos_contribuyente'], $this->declaraciones); exit;
        

        #PASO 2
        $this->load->view('declaraciones/pasos/paso2');

        #PASO 3
        $id_sttm_form = $sttm_tax['tax'][$sttm_tax['tax_account_number']]->id_sttm_form;

        if ($id_sttm_form > 0){
            $paso3['actividades_contribuyente'] = $this->declaraciones->get_data_statement($id_sttm_form);
        }else{
            $paso3['actividades_contribuyente'] = $this->declaraciones->tax_activities($id_tax, $this->sttm_properties->fiscal_year);
        }

        #dd($this->declaraciones, $paso3);

        $paso3['actividades_permisadas'] = $this->declaraciones->get_activities($this->sttm_properties->fiscal_year);

        #dd($this->declaraciones, $paso3['actividades_permisadas']);

        $paso3['unidad_tributaria'] = $this->declaraciones->get_tax_unit($this->sttm_properties->fiscal_year);
        $this->load->view('declaraciones/pasos/paso3', $paso3);

        #var_dump($this->declaraciones, $paso3); exit;

        #PASO 4

        if ($paso1['showStepFour'])
        {
            foreach ($paso3['actividades_contribuyente'] AS $i => $act)
            {
                $paso3['actividades_contribuyente'][$i]->parent_specialized = $this->declaraciones->get_children_tax_classifier_specialized($act->ids_specialized);
            }

            #d($this->declaraciones, $paso3['actividades_contribuyente']); exit;

            $this->load->view('declaraciones/pasos/paso4');
        }

        #PASO 5
        //$paso5['tax_unit'] =  round($paso3['unidad_tributaria']->value axab* $this->getMinimunTle($this->sttm_properties->fiscal_year), 2);
        $paso5['sttm'] = $sttm_tax['sttm'];
        $paso5['sttm_old'] = $this->declaraciones->get_total_sttm($id_tax, $this->sttm_properties->type, $this->sttm_properties->fiscal_year);

        #var_dump($this->declaraciones, $paso5); exit;

        $paso5['tax_discounts'] =  $this->declaraciones->get_tax_discounts($id_tax, $this->sttm_properties->type, $this->sttm_properties->fiscal_year, $this->sttm_properties->month);

        #d($paso5['tax_discounts'], $this->declaraciones);

        $this->load->view('declaraciones/pasos/paso5', $paso5);

        #PASO 6
        $this->load->view('declaraciones/pasos/paso6');

        $this->load->view('footer');
        */

    }

    public function declarar(){

        $sttm_tax = $this->session->userdata('sttm_tax');

        if (!$sttm_tax && (!(isset($_POST)) || empty($_POST))){
            $this->unset_userdata('sttm_tax');
            redirect (site_url ('declaraciones/cuentas'));
        }

        $this->sttm_properties = $this->statement->get_sttm_properties($sttm_tax);
        #dd($sttm_tax, $_POST, $this->sttm_properties);

        $tax_account_number = $sttm_tax['tax_account_number'];
        $id_tax = $sttm_tax['tax'][$tax_account_number]->id_tax;
        $latLong = explode(',', $_POST['latLong']);

        if (isset($_POST['tax_discount']))
        {
            #DESCUENTO COMO EL 219
            if (isset($_POST['tax_discount']['amount_discount']))
            {
                $amount_discount = $this->statement->my_format_number($_POST['tax_discount']['amount_discount']);
                $discount[] = substr($this->statement->to_array_pgsql_data($amount_discount), 1, -1);
            }

            #DESCUENTO COMO PORCENTAJE
            if (isset($_POST['tax_discount']['percent_discount']))
            {
                $percent_discount = $_POST['tax_discount']['percent_discount'];
                $discount[] = substr($this->statement->to_array_pgsql_data($percent_discount), 1, -1);
            }
            
            $discount = '{' . implode(', ', $discount) . '}';
        }

        $discount = isset($discount) ? "'$discount'" : 'NULL';

        $data = array(
            'function' => array(
                'id_tax' => $id_tax,
                'type' => 'TRUE',
                'fiscal_year' => $this->sttm_properties->fiscal_year,
                'activities' => $this->statement->to_array_pgsql_data($_POST['monto']),
                'discount' => $discount,
                'month' => $this->sttm_properties->month
            ),
            'toolbar' => $_POST['toolbar'] + array('id_taxpayer' => $this->id_taxpayer),
            'maps' => array(
                'lat' => trim(@$latLong[0]),
                'long' => trim(@$latLong[1]),
                'json_gm' => (empty($_POST['objGoogleMaps'])) ? '' : json_encode(unserialize($_POST['objGoogleMaps']))
            ),
            'activities_specified' => isset($_POST['last_children']) ? $_POST['last_children'] : FALSE
        );

        #echo serialize($data);

        #dd($_POST, $data);

        $id_sttm_form = $this->declaraciones->save_statement($data);

        #dd($this->declaraciones, $id_sttm_form);

        #var_dump($id_sttm_form); exit;

        if ($id_sttm_form > 0){ #GUARDADO EXITOSAMENTE

            if ($_POST['textSubmit'] == 'liquidar'){
                $id_sttm = $this->declaraciones->liquid_statement($id_sttm_form);

                #dd($id_sttm, $this->declaraciones);

                if ($id_sttm > 0){ #LIQUIDADO CON EXITO
                    $this->messages->add_message("Declaración liquidada exitosamente", "success");
                }else{ #ERROR AL LIQUIDAR
                    $message = "Error al liquidar declaracion, intente de nuevo mas tarde";
                    switch($id_sttm){
                        case 0: $message = "La declaración debe ser guardada primero antes de liquidarla";break;
                        case -1: $message = "No cumple con los requisitos para realizar esta declaración";break;
                    }
                    $this->messages->add_message($message);
                }
                #dd($id_sttm, $this->declaraciones);
            }else{
                $this->messages->add_message("Declaración guardada exitosamente", "success");
            }

        }else{ #ERROR AL GUARDAR
            $message = "Error al guardar declaración, intente de nuevo mas tarde";
            if ($id_sttm_form == -1) $message = "Ya existe una declaración realizada para este período";
            $this->messages->add_message($message);
        }

        #$this->session->unset_userdata('sttm_tax');
        #var_dump($id_sttm_form, @$id_sttm); exit;

        if ($this->messages->have_errors() || ! isset($id_sttm)){
            redirect(site_url('declaraciones/cuentas'));
        }else{
            redirect(site_url("declaraciones/pdf/$id_sttm"));
        }

    }

    public function pdf($id_sttm = NULL) {

        if (! $id_sttm && ! is_numeric($id_sttm))
            redirect(site_url('declaraciones'));

        $data_planilla = $this->declaraciones->get_statement($this->id_taxpayer, $id_sttm);

        #d($data_planilla, $this->declaraciones);

        $referer = @$_SERVER['HTTP_REFERER'];

        if (count($data_planilla) == 0){
            $this->messages->add_message("Esta declaración no existe","notice");
            if ($referer == '' || $referer == site_url('declaraciones')){ #VA A LA LISTA DE DECLARACIONES ANTERIORES
                redirect(site_url('declaraciones'));

            } else { #VA AL LISTADO DE CREAR DECLARACIONES
                redirect(site_url('declaraciones/cuentas'));
            }
        }

        $this->load->library("Planilla",NULL,'pdf');
        $this->pdf->show_statement($data_planilla);

    }

    public function ajax_data_cuenta($tan = NULL){
        $data['error'] = true;
        if ($tan && (strlen($tan) == 9 || strlen($tan) == 16)){
            $data['publicidad'] = $this->declaraciones->get_data_publicidad($tan);
            if (count($data['publicidad']) > 0){
                $data['error'] = false;
                $data['publicidad'] = $data['publicidad'][0];
            }
        }
        #var_dump($tan, $data, strlen($tan));
		echo json_encode($data);
    }

    function _proccess_arrays($cuentasPub, $activitiesDeleted){
        $return = FALSE;
        $arrCuentas = array();
        foreach ($cuentasPub as $tax_account_number => $add){
            if (strlen($tax_account_number) == 8)
                $tax_account_number = str_pad($tax_account_number,9,"0",STR_PAD_LEFT);
            if ($add == 1)
                $arrCuentas[] = $tax_account_number;
        }

        $arrCodes = array();
        foreach ($activitiesDeleted as $code){
            $code = str_replace('_', '.', $code);
            $arrCodes[] = $code;
        }

        return array(
            'publicidad' => $arrCuentas,
            'codesDeleted' => $arrCodes
        );

    }

    function ajax_get_children_tax_classifier_specialized(){

        if (!isset($_GET['ids_specialized']))
            return;
        $field = isset($_GET['field']) ? $_GET['field'] : 'id_parent';
        echo json_encode($this->declaraciones->get_children_tax_classifier_specialized($_GET['ids_specialized'], $field));
    }



}

/* End of file declaraciones.php */
/* Location: ./application/controllers/declaraciones.php */
