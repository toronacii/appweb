<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Oficina_principal extends MY_Controller {
    
    public $cuentas;
    public $taxpayer;

    function __construct() {
        parent::__construct();
        if (!$this->session->userdata('usuario_appweb'))
            redirect(base_url());

        $this->load->model('api_model', 'principal');
        $this->load->model('api_model', 'planilla');
        $this->load->model('api_model', 'news');

        $this->taxpayer = $this->session->userdata('taxpayer');

    }

    public function index() {

        #d($this->taxpayer);

        $header['sidebar'] = 'menu/oficina_menu';
        $header['arrayJs'] = array('bootstrap-dialog/bootstrap-dialog.min.js', 'funciones_oficina.js');
        $header['arrayCss'] = array('oficina.css','bootstrap-dialog/bootstrap-dialog-custom.min.css');
        $header['show_breadcrumbs'] = FALSE;
        $this->load->view('header', $header);

        $data['news'] = $this->news->get_news($this->taxpayer->id_taxpayer);

        $this->load->view('principal/index', $data);

        #d($data, $this->news);

        $this->load->view('footer');
    }

    public function ajax_news()
    {
        extract($_POST);
        var_dump($_POST);
        #d($_POST, $_GET);
        switch ($type)
        {
            case 'mark':
                $r = $this->news->mark($news, $id_taxpayer);
            break;

            case 'unmark':
                $r = $this->news->unmark($news, $id_taxpayer);
            break;

            case 'delete':
                $r = $this->news->delete($news, $id_taxpayer);
            break;
        }
    }

    function edocuenta() {
        $header['sidebar'] = 'menu/oficina_menu';
        $this->load->view('header', $header);
        $data['cuentas'] = $this->session->userdata('taxes');
        $data['tax_types'] = $this->session->userdata('tax_types');
        $data['taxpayer'] = $this->session->userdata('taxpayer');
        $this->load->view('oficina_virtual/edocuenta_view', $data);
        $this->load->view('footer');
    }

/*

    function actualizacion() {


        $this->load->view('oficina_virtual/actualizacion_view');
        $this->load->view('footer');
    }

    

    function tasas() {
        $id = $this->session->userdata('id_taxpayer');
        $data['cuentas'] = $this->oficina->cuentas_usuario($id);
        $this->load->view('oficina_virtual/planilla_tasas_view', $data);
        $this->load->view('footer');
    }

    function impuesto() {
        $id = $this->session->userdata('id_taxpayer');
        $data['cuentas'] = $this->oficina->cuentas_usuario($id);
        $this->load->view('oficina_virtual/planilla_impuesto_view', $data);
        $this->load->view('footer');
    }

    function perfil() {
        $id = $this->session->userdata('id_taxpayer');
        $this->load->model('principal_model');
        $this->load->model('gestion_usuario_model');

        if (isset($_POST['modperfil'])) {
            $data['mod'] = 1;
            $data['mensaje'] = 'SI DESEA REALIZAR ALGUNA MODIFICACION EN SU PERFIL DEBE INGRESAR SU CONTRASEÑA ACTUAL';
        } else if (isset($_POST['guardar'])) {
            $data['mod'] = 0;

            if ($_POST['validpass'] != '') {

                $valid_pass = $this->principal_model->validar_password($id, $_POST['validpass']);

                if (@$valid_pass[0]->id_taxpayer != '') {
                    $this->oficina->act_perfil($id, $_POST['local'], $_POST['cel']);
                    $pass = @$_POST['pass'];
                    $confirm = @$_POST['confirmpass'];
                    if ($pass != '') {

                        if (trim($pass) == trim($confirm)) {

                            if ($array_json['valid'] = $this->gestion_usuario_model->existe_email_usuario($_POST['email'])) {

                               $data = array(
                                    'email' => $_POST['email'],
                                    'message' => 'Gracias por registrarse en la Oficina Virtual de la Direccion de Rentas,<br/><br/>Su Usuario es: ' . $_POST['email'] . '<br /> Su Nueva Clave : ' . $_POST['pass'] . '<br/>En este mismo buzon recibira comunicaciones importantes y confirmacion de sus transacciones.<br/>IMPORTANTE: Le recomendamos imprimir esta informacion y guardarla en un sitio seguro, o conserve una copia digital de este mensaje'
                                );
                                if ($array_json['email_send'] = $this->gestion_usuario_model->send_email_WS($data)) {

                                    $this->principal_model->cambiar_password($_POST['email'], $_POST['pass']);
                                    $data['mensaje'] = "ACTUALIZACION DE DATOS EXITOSO";
                                }
                            }
                        } else {
                            $data['mensaje'] = "CONFIRMACION DE NUEVA CONTRASENA ERRADA, VERIFIQUE Y VUELVA A INTENTARLO";
                        }
                    }
                } else {
                    $data['mensaje'] = "CONTRASENA ACTUAL INCORRECTA, VERIFIQUE Y VUELVA A INTENTARLO";
                }
            } else {
                $data['mensaje'] = "DEBE INGRESAR LA CONTRASEÑA ACTUAL PARA PODER REALIZAR MODIFICACIONES";
            }
        } else {
            $data['mod'] = 0;
            $data['mensaje'] = 'SI DESEA REALIZAR ALGUNA MODIFICACION EN SU PERFIL DEBE INGRESAR SU CONTRASEÑA ACTUAL';
        }
        $data['result'] = $this->oficina->perfil($id);
        $this->load->view('oficina_virtual/perfil_view', $data);
        $this->load->view('footer');
    }

    function info_cuenta($cuenta) {
        if (!$this->uri->segment(3))
            redirect(site_url('oficina_principal'));
        $this->load->model('edocuenta_model', 'edocuenta');
        $data['result'] = $this->planilla->infocontribuyente($cuenta);
        $data['clasificacion'] = $this->edocuenta->Buscar_Clasificadores($data['result'][0]->tributo);
        if ($data['clasificacion'] == null)
            $data['clasificacion'] = $this->edocuenta->Buscar_Clasificadores2($data['result'][0]->tributo);
        $data['campos'] = $this->edocuenta->Buscar_Campos($data['result'][0]->tributo);
        $data['aditional'] = $this->edocuenta->info_adicional($data['result'][0]->tributo);

        $this->load->view('oficina_virtual/info_cuenta_view', $data);
        $this->load->view('footer');
    }

    function generadas($tipo) {
        $id = $this->session->userdata('id_taxpayer');
        $this->load->model('generadas_model', 'generadas');
        if ($tipo == 'n')
            $data['planilla'] = $this->generadas->verificarplanillas($id);
        else
            $data['planilla'] = $this->generadas->planillaspagadas($id);
        $data['tipoplan'] = $tipo;
        $this->load->view('oficina_virtual/generadas_list_view', $data);
        $this->load->view('footer');
    }

/*
    function tramitesae() {
        $id = $this->session->userdata('id_taxpayer');
        $data['cuentas'] = $this->oficina->cuentas_usuario($id);
        $this->load->view('tramites/tramitesae_view', $data);
        $this->load->view('footer');
    }

    function tramitesinm() {
        $id = $this->session->userdata('id_taxpayer');
        $data['cuentas'] = $this->oficina->cuentas_usuario($id);
        $this->load->view('tramites/tramitesinm_view', $data);
        $this->load->view('footer');
    }


    function procedimientos($idproc) {
        $data['result'] = $this->oficina->info_procedimientos($idproc);
        $this->load->view('oficina_virtual/info_procedimientos_view', $data);
        $this->load->view('footer');
    }

    function nuc() {
        $id = $this->session->userdata('id_taxpayer');

        if (isset($_POST['verificar'])) {
            $cuenta = trim($_POST['cuenta']);
            if ($cuenta != '') {

                $data['result'] = $this->planilla->infocontribuyente($cuenta);
            }
        } else if (isset($_POST['guardar'])) {

            $account = $_POST['confirmcuenta'];

            $type_account = 1;
            $agregar = $this->oficina->agregar_cuenta($id, $type_account, $account);
        }
        $data['asociadas'] = $this->oficina->asociadas_nuc($id);
        $this->load->view('oficina_virtual/cuentas_nuc_view', $data);
        $this->load->view('footer');
    }

    */

   
   
    public function probar_email() {
        
        $this->load->model('gestion_usuario_model');
        
        $datos = 'xxxxxxxxxxxxxxxxxxx';

        $_POST = array(
            'email' => 'toronacii@gmail.com',
            'pass' => 'xxxxxxxxxxxxxxxxxxxxxx123'
        );

        $data = array(
            'email' => $_POST['email'],
            'message' => 'Gracias por registrarse en la Oficina Virtual de la Direccion de Rentas,<br/><br/>Su Usuario es: ' . $_POST['email'] . '<br /> Su Nueva Clave : ' . $_POST['pass'] . '<br/>En este mismo buzon recibira comunicaciones importantes y confirmacion de sus transacciones.<br/>IMPORTANTE: Le recomendamos imprimir esta informacion y guardarla en un sitio seguro, o conserve una copia digital de este mensaje'
        );
        var_dump($this->gestion_usuario_model->send_email_WS($data));
    }

}

/*End of file oficina_principal.php */
/* Location: ./application/controllers/oficina_principal.php */
