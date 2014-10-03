<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Principal extends MY_Controller {

    function __construct() {
        parent::__construct();
        #dd($_SESSION, "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
        if ($this->uri->segment(2) !== 'salir' && $this->session->userdata('usuario_appweb'))
        {
            if (($control = $_GET['control']) && ($page = $this->session->userdata('uri_pago_online')))
            {
                #dd($control, $page);
                redirect("$page/$control");
            }
            redirect(site_url('oficina_principal'));
        }
        $this->session->unset_userdata('usuario_appweb');
        #var_dump($_SESSION);
    }

    public function index() {
        $header['arrayJs'] = array('principal.js');
        $header['sidebar'] = "menu/oficina_menu";
        $this->load->view('header', $header);
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('user', 'Usuario', 'required|trim|valid_email');
        $this->form_validation->set_rules('pass', 'Contrase&ntilde;a', 'required||trim');
        if ($this->form_validation->run() == FALSE) {
            $this->session->unset_userdata('usuario_appweb');

            $this->load->view('principal/principal_view');
        } else {
            $this->load->model('api_model', 'principal');
            $valid_user = $this->principal->valid_user($this->input->post('user'));

            #dd($this->principal, $valid_user); exit;

            if (count($valid_user) > 0) {
                if (sha1($this->input->post('pass')) == @$valid_user->password) {
                    if ($valid_user->confirmed_email == 't') {
                        $this->init_session_vars($valid_user);
                        #var_dump($_SESSION); exit;
                        redirect(site_url('oficina_principal'));
                    }else
                        $data['errorValidacion'] = 'Email sin confirmar';
                }else
                    $data['errorValidacion'] = 'Combinaci&oacute;n de correo y contraseña Inv&aacute;lida';
            }else
                $data['errorValidacion'] = 'Combinaci&oacute;n de correo y contraseña Inv&aacute;lida';
            $data['log'] = '2';
            $this->load->view('principal/principal_view', $data);
        }
        $this->load->view('footer');
    }

    private function init_session_vars($valid_user)
    {
        $this->session->set_userdata('taxpayer', $this->principal->taxpayer($valid_user->id_taxpayer));
        $taxes = objectToArray($this->principal->taxes($valid_user->id_taxpayer));
        $this->session->set_userdata('taxes', $taxes);

        $this->init_tax_types($taxes);
        $this->init_fee_types();
        $this->session->set_userdata('usuario_appweb', $valid_user);
        $this->session->unset_userdata('eventual');

        #dd($this->session->userdata('taxes'));

        #guardar datos de la sesion en base de datos

        $this->principal->save_user_login($valid_user->id, getClientIP());
    }

    private function init_tax_types($taxes){

        $tax_types = array(

            1 => (object)array(
                'name' => 'Actividades económicas',
                'total' => 0
            ),
            2 => (object)array(
                'name' => 'Inmuebles urbanos',
                'total' => 0
            ),
            3 => (object)array(
                'name' => 'Vehículos',
                'total' => 0
            ),
            4 => (object)array(
                'name' => 'Publicidad fija',
                'total' => 0
            ),
            5 => (object)array(
                'name' => 'Publicidad eventual',
                'total' => 0
            ),
            6 => (object)array(
                'name' => 'Vallas',
                'total' => 0
            ),

        );

        $total_taxes = 0;

        foreach ($taxes as $cuenta){

            switch ($cuenta->id_tax_type){
                case 1: $tax_types[1]->total++; break;
                case 2: $tax_types[2]->total++; break;
                case 3: $tax_types[3]->total++; break;
                case 4: $tax_types[4]->total++; break;
                case 5: $tax_types[5]->total++; break;
                case 6: $tax_types[6]->total++; break;
            }

            $total_taxes++;
        }

        #var_dump($this->cuentas);

        $this->session->set_userdata('total_taxes',$total_taxes);
        $this->session->set_userdata('tax_types', $tax_types);

    }

    private function init_fee_types()
    {
        $this->load->model('api_model', 'planillas');
        $tax_types = $this->session->userdata('tax_types');

        $ids_fee_types = array();

        foreach ($tax_types as $id => $tax_type){
            if ($tax_type->total)
                $ids_fee_types[] = $id;
        }

        $this->session->set_userdata('tipos_tasas', (array)$this->planillas->tipos_tasas($ids_fee_types));
    }

    private function genera_password() {
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $cad = '';
        for ($i = 0; $i < 8; $i++) {
            $cad .= substr($str, rand(0, 62), 1);
            if ($i == 7 && !preg_match('/^.*([a-zA-Z][\d]|[\d][a-zA-Z]).*$/', $cad)) {
                $i = 0;
                $cad = '';
            }
        }
        return $cad;
    }

    public function ajax_olvido_password() {
        $this->load->model('api_model', 'gestion_usuario');
        $this->load->model('api_model', 'principal');
        $email = $_GET['email'];
        if ($array_json['valid'] = $this->gestion_usuario->existe_email_usuario($email)) {
            $pass = $this->genera_password();
            $data = array(
                'email' => $email,
                'password' => $pass,
                'view' => 'emails.recuperar_password'
            );

            if ($array_json['email_send'] = $this->gestion_usuario->send_email_WS($data)) {
                $this->principal->cambiar_password($email, $pass);
            }
        }
        #var_dump($this->curl->error_code, $this->curl->error_string, $this->curl->info);
        echo json_encode($array_json);
    }

    public function in($log) {
        $data['log'] = $log;
        $header['arrayCss'] = array('ui-lightness/jquery-ui-1.8.20.custom.css', 'style_principal.css');
        $header['arrayJs'] = array('jquery-ui-1.8.20.dialog.min.js', 'principal.js');
        $this->load->view('header', $header);
        $this->load->view('principal/principal_view', $data);
        $this->load->view('footer');
    }

    public function salir(){
        session_destroy();
        redirect("http://www.alcaldiamunicipiosucre.gov.ve/contenido/alcaldia/organigrama/direccion-de-rentas/");
        #redirect(site_url());
    }

    public function probar_email() {

        $this->load->model('api_model', 'gestion_usuario');

        $datos = 'xxxxxxxxxxxxxxxxxxx';
        $_GET['email'] = 'toronacii@gmail.com';
        $_GET['pass'] = '123456789';

        $data = array(
            'email' => $_GET['email'],
            'mensaje' => "Usuario: " . $_GET['email'] . "Contrase&ntilde;a: " . $_GET['pass']
        );
        var_dump($this->gestion_usuario->send_email_WS($data));
        var_dump($this->curl->error_code, $this->curl->error_string, $this->curl->info);
    }

    public function prueba()
    {
        $this->load->model('api_model', 'principal');
        $this->load->model('api_model', 'gestion_usuario');

        #var_dump($this->principal->valid_user('x6350@x.com'));

        #var_dump($this->principal->cambiar_password('x6350@x.com', '123'));

        #var_dump($this->gestion_usuario->existe_email_usuario('x6350@x.com'));

        #$data['email'] = 'toronacii@gmail.com';

        #var_dump($this->gestion_usuario->send_email_WS(serialize($data)));

        #var_dump($this->principal->taxpayer(6350));

        #var_dump($this->principal->taxes(6350));

        #$data = unserialize('a:2:{s:11:"regenerated";i:1390138732;s:17:"datos_principales";s:407:"a:13:{s:12:"tipo_persona";s:7:"natural";s:6:"cedula";s:10:"19.387.920";s:3:"rif";s:0:"";s:12:"razon_social";s:0:"";s:7:"nombres";s:4:"JOSE";s:9:"apellidos";s:4:"TORO";s:9:"tlf_local";s:14:"0212-546-54-65";s:11:"tlf_celular";s:14:"0412-456-46-54";s:5:"email";s:19:"toronacii@gmail.com";s:10:"conf_email";s:19:"toronacii@gmail.com";s:4:"pass";s:6:"abc123";s:9:"conf_pass";s:6:"abc123";s:8:"contrato";s:1:"1";}";}');

        #var_dump($this->gestion_usuario->registrar_contribuyente($data));

        #echo $this->curl->info['url'];

        #var_dump($this->curl->error_code, $this->curl->error_string, $this->curl->info);

        $data = unserialize('a:3:{s:13:"datos_basicos";a:13:{s:12:"tipo_persona";s:7:"natural";s:6:"cedula";s:10:"19.387.920";s:3:"rif";s:0:"";s:12:"razon_social";s:0:"";s:7:"nombres";s:4:"Jose";s:9:"apellidos";s:4:"Toro";s:9:"tlf_local";s:14:"0212-454-68-78";s:11:"tlf_celular";s:14:"0412-565-65-66";s:5:"email";s:19:"toronacii@gmail.com";s:10:"conf_email";s:19:"toronacii@gmail.com";s:4:"pass";s:8:"carpe123";s:9:"conf_pass";s:8:"carpe123";s:8:"contrato";s:1:"1";}s:11:"id_taxpayer";s:7:"1020698";s:18:"cuentas_reportadas";N;}');

        var_dump($this->gestion_usuario->registrar_contribuyente($data), $this->gestion_usuario->errors);
    }

    public function prueba_post()
    {
        $this->load->model('api_model', 'tramites');

        $x = unserialize('a:3:{s:13:"datos_basicos";a:13:{s:12:"tipo_persona";s:7:"natural";s:6:"cedula";s:10:"19.387.920";s:3:"rif";s:0:"";s:12:"razon_social";s:0:"";s:7:"nombres";s:4:"Jose";s:9:"apellidos";s:4:"Toro";s:9:"tlf_local";s:14:"0212-454-68-78";s:11:"tlf_celular";s:14:"0412-565-65-66";s:5:"email";s:19:"toronacii@gmail.com";s:10:"conf_email";s:19:"toronacii@gmail.com";s:4:"pass";s:8:"carpe123";s:9:"conf_pass";s:8:"carpe123";s:8:"contrato";s:1:"1";}s:11:"id_taxpayer";s:7:"1020698";s:18:"cuentas_reportadas";N;}');

        $y = json_decode('[{"address_components":[{"long_name":"Avenida Rep\u00fablica Dominicana","short_name":"Avenida Rep\u00fablica Dominicana","types":["route"]},{"long_name":"Bole\u00edta Sur","short_name":"Bole\u00edta Sur","types":["neighborhood","political"]},{"long_name":"Bole\u00edta","short_name":"Bole\u00edta","types":["sublocality","political"]},{"long_name":"Avienda Turin","short_name":"Avienda Turin","types":["locality","political"]},{"long_name":"El Hatillo","short_name":"El Hatillo","types":["administrative_area_level_2","political"]},{"long_name":"Distrito Metropolitano de Caracas","short_name":"Dto. Capital","types":["administrative_area_level_1","political"]},{"long_name":"Venezuela","short_name":"VE","types":["country","political"]}],"formatted_address":"Avenida Rep\u00fablica Dominicana, Avienda Turin, Venezuela","geometry":{"bounds":{"ea":{"d":10.4857088,"b":10.489138},"fa":{"b":-66.8203556,"d":-66.8195252}},"location":{"lb":10.4874961,"mb":-66.8201673},"location_type":"APPROXIMATE","viewport":{"ea":{"d":10.4857088,"b":10.489138},"fa":{"b":-66.821289380291,"d":-66.818591419708}}},"types":["route"]},{"address_components":[{"long_name":"Bole\u00edta Sur","short_name":"Bole\u00edta Sur","types":["neighborhood","political"]},{"long_name":"Bole\u00edta","short_name":"Bole\u00edta","types":["sublocality","political"]},{"long_name":"Caracas","short_name":"CCS","types":["locality","political"]},{"long_name":"Sucre","short_name":"Sucre","types":["administrative_area_level_2","political"]},{"long_name":"Miranda","short_name":"Miranda","types":["administrative_area_level_1","political"]},{"long_name":"Venezuela","short_name":"VE","types":["country","political"]}],"formatted_address":"Bole\u00edta Sur, Caracas, Venezuela","geometry":{"bounds":{"ea":{"d":10.4839214,"b":10.4938872},"fa":{"b":-66.82623,"d":-66.8173912}},"location":{"lb":10.4892193,"mb":-66.8215981},"location_type":"APPROXIMATE","viewport":{"ea":{"d":10.4839214,"b":10.4938872},"fa":{"b":-66.82623,"d":-66.8173912}}},"types":["neighborhood","political"]},{"address_components":[{"long_name":"Bole\u00edta","short_name":"Bole\u00edta","types":["sublocality","political"]},{"long_name":"Caracas","short_name":"CCS","types":["locality","political"]},{"long_name":"Sucre","short_name":"Sucre","types":["administrative_area_level_2","political"]},{"long_name":"Miranda","short_name":"Miranda","types":["administrative_area_level_1","political"]},{"long_name":"Venezuela","short_name":"VE","types":["country","political"]}],"formatted_address":"Bole\u00edta, Caracas, Venezuela","geometry":{"bounds":{"ea":{"d":10.4839424,"b":10.5006497},"fa":{"b":-66.82623,"d":-66.8173966}},"location":{"lb":10.4918439,"mb":-66.8215981},"location_type":"APPROXIMATE","viewport":{"ea":{"d":10.4839424,"b":10.5006497},"fa":{"b":-66.82623,"d":-66.8173966}}},"types":["sublocality","political"]},{"address_components":[{"long_name":"Sucre","short_name":"Sucre","types":["administrative_area_level_2","political"]},{"long_name":"Miranda","short_name":"Miranda","types":["administrative_area_level_1","political"]},{"long_name":"Venezuela","short_name":"VE","types":["country","political"]}],"formatted_address":"Sucre, Venezuela","geometry":{"bounds":{"ea":{"d":10.4381074,"b":10.5122233},"fa":{"b":-66.8441364,"d":-66.7179729}},"location":{"lb":10.4762676,"mb":-66.7724653},"location_type":"APPROXIMATE","viewport":{"ea":{"d":10.4381074,"b":10.5122233},"fa":{"b":-66.8441364,"d":-66.7179729}}},"types":["administrative_area_level_2","political"]},{"address_components":[{"long_name":"Caracas","short_name":"CCS","types":["locality","political"]},{"long_name":"Municipio Libertador de Caracas","short_name":"Municipio Libertador de Caracas","types":["administrative_area_level_2","political"]},{"long_name":"Distrito Metropolitano de Caracas","short_name":"Dto. Capital","types":["administrative_area_level_1","political"]},{"long_name":"Venezuela","short_name":"VE","types":["country","political"]}],"formatted_address":"Caracas, Venezuela","geometry":{"bounds":{"ea":{"d":10.39665,"b":10.5401335},"fa":{"b":-67.0627784,"d":-66.7179533}},"location":{"lb":10.491016,"mb":-66.902061},"location_type":"APPROXIMATE","viewport":{"ea":{"d":10.39665,"b":10.5401335},"fa":{"b":-67.0627784,"d":-66.7179533}}},"types":["locality","political"]},{"address_components":[{"long_name":"Miranda","short_name":"Miranda","types":["administrative_area_level_1","political"]},{"long_name":"Venezuela","short_name":"VE","types":["country","political"]}],"formatted_address":"Miranda, Venezuela","geometry":{"bounds":{"ea":{"d":9.966907,"b":10.645797},"fa":{"b":-67.1727881,"d":-65.40244}},"location":{"lb":10.2509303,"mb":-66.4271499},"location_type":"APPROXIMATE","viewport":{"ea":{"d":9.966907,"b":10.645797},"fa":{"b":-67.1727881,"d":-65.40244}}},"types":["administrative_area_level_1","political"]},{"address_components":[{"long_name":"Venezuela","short_name":"VE","types":["country","political"]}],"formatted_address":"Venezuela","geometry":{"bounds":{"ea":{"d":0.6475291,"b":12.4866941},"fa":{"b":-73.3515581,"d":-59.805666}},"location":{"lb":6.42375,"mb":-66.58973},"location_type":"APPROXIMATE","viewport":{"ea":{"d":0.6475291,"b":12.4866941},"fa":{"b":-73.3515581,"d":-59.805666}}},"types":["country","political"]}]');

        $r = $this->tramites->prueba_post(array($x, $y));

        var_dump($r, $this->tramites);
    }

}

/* End of file principal.php */
/* Location: ./application/controllers/principal.php */
