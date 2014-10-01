<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Generar_planilla extends MY_Controller {

    function __construct() {
        parent::__construct();
        #$this->load->model('gestion_usuario_model', 'querys');
        #$this->load->model('planilla_model', 'planilla');
        $this->load->model('api_model', 'gestion_usuario');
        $this->load->model('api_model', 'planillas');
    }

  
    function unificada($id_invoice = NULL){
        
        if ($id_invoice){
            if (! in_array($id_invoice, $this->session->userdata('planillas_imprimir')))
                redirect(site_url('planillas_pago/generadas'));

        }elseif (! $id_invoice = $this->session->userdata('id_invoice'))
            redirect(site_url('planilla_unificada'));
        
        $this->load->library('Planilla.php');
        $planilla = new Planilla();
        $planilla->mostrar_unificada($id_invoice);    
    }
    
    function genera_planilla() {
        $this->load->library('Planilla.php');
        $planilla = new Planilla();

        if ($this->session->userdata('planilla_tasa') != '') {

            $planilla->Generar($this->session->userdata('planilla_tasa'));
            $this->session->unset_userdata('planilla_tasa');
        } else if ($this->session->userdata('planilla') != '') {

            $planilla->Generar($this->session->userdata('planilla'));
            $this->session->unset_userdata('planilla');
        } 
    }

    function imprime_planilla($id_invoice = NULL){
        
        if ($id_invoice){
            if (! in_array($id_invoice, $this->session->userdata('planillas_imprimir')))
                redirect(site_url('planillas_pago/generadas'));

        }else if (! $id_invoice = $this->session->userdata('id_invoice')){
            redirect(site_url('planillas_pago/tasas'));
        }

        $this->load->library('Planilla.php');
        $planilla = new Planilla();

        $planilla->show_invoice($id_invoice);
        
    }

    function imprime_solvencia()
    {
        $this->load->library('Planilla.php');
        $planilla = new Planilla();
        $data = array(
            'id_request' => $this->session->userdata('id_request'),
            'taxpayer' => $this->session->userdata('taxpayer')
        );
        $planilla->generar_recibo_tramite($data);
    }

    function imprime_pago_megasoft($control)
    {
        if (! $control = $this->session->userdata('control'))
        {
            redirect(site_url());
        }

        $this->load->library('Planilla.php');
        $planilla = new Planilla();

        $planilla->print_invoice_megasoft($control);
    }

    public function probar_email() {

        $dif = 'xxxxxxxxxxxxxxxxxxx';

        $data = array(
            'id_tax' => 'toronacii@gmail.com',
            'orderId' => 'xxxxxxxxxxxxxxxxxxxxxx123',
            'monto_planilla' => 'xxxxxxxxxxxxxxxxxxxxxx123',
            'total' => 'xxxxxxxxxxxxxxxxxxxxxx123',
        );

        $data = array(
            'emails' => 'toronacii@gmail.com, toronacii.ml@gmail.com',
            'subject' => "DIFERENCIA DE PAGO EN LINEA ({$dif})",
            'message' => "<h2>Error appweb</h2><br>Id tax : {$data['id_tax']} <br>Planilla : {$data['orderId']} <br>Monto correcto: {$data['monto_planilla']} <br>Monto appweb: {$data['total']} <br><br><strong>Ojo: el monto que se envio a credicard es {$data['monto_planilla']}</strong>"
        );
        var_dump($this->querys->send_email_WS($data));
    }

}

