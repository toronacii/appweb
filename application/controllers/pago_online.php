<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pago_Online extends MY_Controller {

    public $id_taxpayer;
    public $messages;

    public function __construct() 
    {
        parent::__construct();
        if (! $this->id_taxpayer = $this->session->userdata('taxpayer')->id_taxpayer)
        {
            redirect(base_url());
        }

        $this->load->library("MessageSession");
        $this->messages = new MessageSession();
        $this->load->library('Payment');
    }

    public function index($id_invoice)
    {
        #dd($_SERVER);
        if (! ($id_invoice) || ! preg_match('/^[\d]+$/', $id_invoice))
        {
            redirect(site_url());
        }

        $url_origin = $this->session->userdata('uri_pago_online');
        
        try
        {
            $control_number = $this->payment->set_control_number($id_invoice, $url_origin);
            $config = (object)$this->CI->config->config['megasoft'];
            redirect(str_replace("@control", $control_number, $config->redirect));
        }
        catch (Exception $e) {
            $this->messages->add_message($e->getMessage());
            redirect($url_origin);
        }
    }

    public function compensate()
    {
        if (! isset($_GET['control']))
            exit;

        $control = $_GET['control'];

        $online_payment = $this->pago_online->get_payment_by_control($control);
        try
        {
            $redirect_to = $this->payment->compensate($online_payment);
            redirect($redirect_to);
        }
        catch(Exception $e)
        {
            $this->messages->add_message($e->getMessage());
            redirect($online_payment->pagina);
        }
    }

}