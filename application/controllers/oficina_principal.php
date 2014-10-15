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

        $header['sidebar'] = 'menu/oficina_menu';
        $header['arrayJs'] = array('bootstrap-dialog/bootstrap-dialog.min.js','angular/angular.min.js','angular/simplePagination.js','angular/principal.js');
        $header['arrayCss'] = array('oficina.css','bootstrap-dialog/bootstrap-dialog-custom.min.css');
        $header['show_breadcrumbs'] = FALSE;
        $this->load->view('header', $header);

        $this->load->view('principal/news_angular');

        #d($data, $this->news);

        $this->load->view('footer');
    }

    public function ajax_news()
    {
        extract($_POST);
        var_dump($_POST);
        #d($_POST, $_GET);
        $id_taxpayer = $this->taxpayer->id_taxpayer;
        switch ($type)
        {
            case 'read':
                $r = $this->news->mark($news, $id_taxpayer);
            break;

            case 'unread':
                $r = $this->news->unmark($news, $id_taxpayer);
            break;

            case 'delete':
                $r = $this->news->delete($news, $id_taxpayer);
            break;
        }
        var_dump($type, $r, $this->news);
    }

    public function api_get_news()
    {
        $news = $this->news->get_news($this->taxpayer->id_taxpayer);

        #d($this->news,$news);

        foreach ($news as $i => $new) {
            $news[$i]->message_strip_tags = strip_tags($new->message);
            $news[$i]->created = ($new->created == date('Y-m-d')) ? "HOY" : date('d/m/Y', strtotime($new->created));
        }

        echo json_encode($news);
    }

    public function edocuenta() {
        $header['sidebar'] = 'menu/oficina_menu';
        $this->load->view('header', $header);
        $data['tax_types'] = $this->session->userdata('tax_types');
        $data['taxpayer'] = $this->session->userdata('taxpayer');
        $data['cuentas'] = $this->principal->edo_cuenta($data['taxpayer']->id_taxpayer);

        #dd($data, $this->principal);
        $this->load->view('oficina_virtual/edocuenta_view', $data);
        $this->load->view('footer');
    }

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
