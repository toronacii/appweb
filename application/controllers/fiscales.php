<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fiscales extends MY_Controller {

	public $taxes;

	function __construct() {
        parent::__construct();
    }

    function index() {

        $header['sidebar'] = 'menu/oficina_menu';

        $this->load->view('header', $header);

        $data = array();

        $this->error = FALSE;

        if (isset($_POST) && !empty($_POST)) {
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
            $this->form_validation->set_rules('cedula', 'Cedula', 'required|trim|numeric|callback__fiscal_exists');
            if ($this->form_validation->run()) 
            {
                $cedula = $_POST['cedula'];
                $data['fiscal'] = $this->fiscal;
            }
            else
            {
                $this->error = TRUE;
            }
        }

        $this->load->view('fiscales/consulta_view', $data);

        $this->load->view('footer');
    }

    public function _fiscal_exists($cedula) {

        $this->load->model('api_model', 'tramites');

        if (! $this->fiscal = $this->tramites->get_fiscal($cedula)) {
            #var_dump($this->fiscal,  $this->tramites);
            $this->error = TRUE;
            $this->form_validation->set_message('_fiscal_exists', 'No existe este fiscal');
            return FALSE;
        }

        return TRUE;
    }

}