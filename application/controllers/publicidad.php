<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Publicidad extends MY_Controller {

    public $tipo;

    function __construct()
    {
        parent::__construct();
        $this->load->model('api_model', 'publicidad');
    }

    function calculadora()
    {
        $this->load->model('api_model', 'declaraciones');

        $header['arrayCss'] = array("publicidad.css");
        $header['arrayJs'] = array("round.js", "number_format.js", "funciones_publicidad.js");
        $header['sidebar'] = 'menu/oficina_menu';

        $this->load->view('header', $header);

        $data['classifiers'] = $this->publicidad->get_publicidad();
        $data['tax_unit'] = $this->declaraciones->get_tax_unit(date('Y'))->value;
        #var_dump();
        $this->load->view('publicidad/calculadora_view', $data);

        $this->load->view('footer');
    }
    
    function generaPDF(){
        
        if (!isset($_POST) || empty($_POST))
            redirect(site_url('calculadorap/index/fija'));
        
        $this->load->library("Planilla",NULL,'pdf');
        #var_dump($_POST);
        $this->pdf->genera_calc_PU($_POST);
    }

}

/* End of file calculadoraP.php */
/* Location: ./application/controllers/calculadoraP.php */