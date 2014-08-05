<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gestion_usuario extends MY_Controller {

    private $paso_actual = 1;
    private $sess_destroy = FALSE;

    function __construct() {
        parent::__construct();
        $header['arrayCss'] = array('ui-lightness/jquery-ui-1.10.3.custom.min.css'/*'ui-lightness/jquery-ui-1.8.20.custom.css', 'style_gestion_usuario.css'*/);
        $header['arrayJs'] = array('bootstrap/jquery-ui-1.10.3.custom.min.js','bootstrap/bootstrap-captcha.js','serialize.js', 'str_pad.js', 'funciones_gestion_usuario.js'/*'jquery-ui-1.8.20.dialog.min.js', 'jquery.pstrength-min.1.2.js', , 'jquery.elastic.source.js', 'principal.js'*/);
        $header['sidebar'] = 'menu/oficina_menu';
        $this->load->view('header', $header);
        $this->load->model('api_model', 'gestion_usuario');
        $this->load->model('api_model', 'tramites');

        #$_SESSION = unserialize('a:2:{s:11:"regenerated";i:1390138732;s:17:"datos_principales";s:407:"a:13:{s:12:"tipo_persona";s:7:"natural";s:6:"cedula";s:10:"19.387.920";s:3:"rif";s:0:"";s:12:"razon_social";s:0:"";s:7:"nombres";s:4:"JOSE";s:9:"apellidos";s:4:"TORO";s:9:"tlf_local";s:14:"0212-546-54-65";s:11:"tlf_celular";s:14:"0412-456-46-54";s:5:"email";s:19:"toronacii@gmail.com";s:10:"conf_email";s:19:"toronacii@gmail.com";s:4:"pass";s:6:"abc123";s:9:"conf_pass";s:6:"abc123";s:8:"contrato";s:1:"1";}";}');
        #$this->session->unset_userdata('cuentas');
        #$_SESSION['cuentas'] = 'a:4:{s:5:"usado";b:0;s:7:"cuentas";a:1:{i:0;O:8:"stdClass":2:{s:12:"rent_account";s:16:"97-5-001-00096-P";s:18:"tax_account_number";s:9:"030001235";}}s:8:"tiene_AE";i:1;s:11:"id_taxpayer";s:6:"109737";}';
        #var_dump(serialize($_SESSION));
        #var_dump(unserialize($_POST['data_enviada'])); exit;
    }

    public function registro() {

        $data = array();

        $this->paso_actual = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
        if (!$this->session->userdata('datos_principales')) {
            if ($this->paso_actual != 1)
                redirect('gestion_usuario/registro');
        }else if (!$this->session->userdata('cuentas')) {
            if ($this->paso_actual != 2)
                redirect('gestion_usuario/registro/2');
        }else if (!$this->session->userdata('cuentas_reportadas')) {
            if ($this->paso_actual != 3)
                redirect('gestion_usuario/registro/3');
        }else if (!$this->session->userdata('respuestas_correctas')) {
            if ($this->paso_actual != 4)
                redirect('gestion_usuario/registro/4');
        }else if ($this->paso_actual != 5) {
            redirect('gestion_usuario/registro/5');
        } else if ($this->session->userdata('registro')) {

            $this->session->unset_userdata('datos_principales');
            $this->session->unset_userdata('cuentas');
            $this->session->unset_userdata('cuentas_reportadas');
            $this->session->unset_userdata('ultima_planilla_pagada');
            $this->session->unset_userdata('ultimo_numero_declaracion');
            $this->session->unset_userdata('respuestas_correctas');
            $this->sess_destroy = TRUE;
            $this->session->unset_userdata('registro');
            redirect('gestion_usuario/registro');

        }
        //$this->session->unset_userdata('respuestas_correctas');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><small>', '</small><a href="#" class="close" aria-hidden="true" data-dismiss="alert">&times;</a></div>');
        #echo "<pre>";print_r($_POST);exit;
#echo $_SERVER['SERVER_NAME'];

        switch ($this->paso_actual) {
            case 1: //DATOS PRINCIPALES
                $this->session->unset_userdata('registro');
                if ($this->input->post('tipo_persona') == 'natural') {
                    $this->form_validation->set_rules('cedula', 'Cedula', 'required|trim');
                } else {
                    $this->form_validation->set_rules('razon_social', 'Raz&oacute;n social', 'required|trim');
                    $this->form_validation->set_rules('rif', 'RIF', 'trim|callback_validar_rif');
                }
                $this->form_validation->set_rules('tipo_persona', '', 'trim');
                $this->form_validation->set_rules('nombres', 'Nombres', 'required|trim');
                $this->form_validation->set_rules('apellidos', 'Apellidos', 'required|trim');
                $this->form_validation->set_rules('tlf_local', 'Tel&eacute;fono Local', 'required|trim|callback_validarTelefono[local]');
                $this->form_validation->set_rules('tlf_celular', 'Tel&eacute;fono Celular', 'required|trim||callback_validarTelefono[celular]');
                $this->form_validation->set_rules('pass', 'Contrase&ntilde;a', 'required|trim|callback_validaciones_password|matches[conf_pass]');
                $this->form_validation->set_rules('conf_pass', 'Confirmar Contrase&ntilde;a', 'required|trim');
                $this->form_validation->set_rules('email', 'Correo Electr&oacute;nico', 'required|trim|valid_email|matches[conf_email]|callback_validar_email');
                $this->form_validation->set_rules('conf_email', 'Confirmar Correo Electr&oacute;nico', 'required|trim');
                $this->form_validation->set_rules('contrato', 'Contrato', 'required');

                break;
            case 2: //DATOS DE CUENTAS
                if ($this->input->post('volver')) {
                    $this->session->unset_userdata('datos_principales');
                    redirect('gestion_usuario/registro/1');
                }
                if ($tipo_cuenta = $this->input->post('tipo_cuenta')) {
                    //echo $this->paso_actual;
                    #var_dump($_POST);
                    $this->form_validation->set_rules('tipo_cuenta', '', "trim");
                    $callback = ($tipo_cuenta == 'cuentarenta') ? "validacionesCuentaRenta" : "validacionesCuentaNueva";
                    $this->form_validation->set_rules($tipo_cuenta, 'Tipo de Cuenta', "required|trim|callback_$callback");
                }
                break;
            case 3:
                if ($this->input->post('volver')) {
                    $this->session->unset_userdata('cuentas');
                    $this->session->unset_userdata('ultima_planilla_pagada');
                    $this->session->unset_userdata('ultimo_numero_declaracion');
                    redirect('gestion_usuario/registro/2');
                }
                if ($this->input->post('data_enviada')) {
                    $this->form_validation->set_rules('data_enviada', NULL, "trim");
                }
                break;
            case 4:
                $cuentas = $this->session->userdata('cuentas');


                if (!$this->session->userdata('ultima_planilla_pagada')) {

                    $planillas = $this->gestion_usuario->ultima_planilla_pagada($cuentas['id_taxpayer']);

                    if ($planillas->posee_planillas == 1)
                        $this->session->set_userdata('ultima_planilla_pagada', $planillas);
                }
                //verificacion de planillas de declaracion
                if (!$this->session->userdata('ultimo_numero_declaracion')) {
                    #$decla = $this->querys->ultimo_numero_declaracion($cuentas['id_taxpayer']);
                    /* echo"<pre>";
                      print_r($decla);
                      echo"</pre>"; */
                    $this->session->set_userdata('ultimo_numero_declaracion', $this->gestion_usuario->ultimo_numero_declaracion($cuentas['id_taxpayer']));
                }

                if ($this->input->post('volver')) {
                    $this->session->unset_userdata('cuentas_reportadas');
                    redirect('gestion_usuario/registro/3');
                } else if ($this->input->post('preguntas_validacion')) { //VALIDACION
                    $ultima_planilla_pagada = $this->session->userdata('ultima_planilla_pagada');
                    if ($ultima_planilla_pagada->posee_planillas == 1)
                        $this->form_validation->set_rules('ult_planilla', 'N&uacute;mero de Planilla', "required|trim|callback_validar_ult_planilla");
                    $this->form_validation->set_rules('ult_declaracion', 'N&uacute;mero de Declaraci&oacute;n', "required|trim|callback_validar_ult_declaracion");
                }
                break;
            case 5:
                if (!$this->sess_destroy) {
                    $cuentas = $this->session->userdata('cuentas');
                    $cuentas_reportadas_bruto = unserialize($this->session->userdata('cuentas_reportadas'));
                    $datos_insertar['datos_basicos'] = unserialize($this->session->userdata('datos_principales'));
                    $datos_insertar['id_taxpayer'] = $cuentas['id_taxpayer'];
                    $cuentas_reportadas = "";
                    foreach ($cuentas_reportadas_bruto as $indObj => $objCuentas) {
                        foreach ($objCuentas as $cuenta) {
                            if (!empty($cuenta))
                                $cuentas_reportadas[$indObj][] = $cuenta;
                        }
                    }
                    $datos_insertar['cuentas_reportadas'] = $cuentas_reportadas;

                    $resp['id_user'] = $this->gestion_usuario->registrar_contribuyente($datos_insertar);
                    #dd($resp, $this->gestion_usuario);
                    #echo "<pre>";print_r($resp);echo "</pre>";//exit;

                    #var_dump($datos_insertar); exit;
                    //Email
                    if ($resp['id_user']) {
                        $datos_principales = unserialize($this->session->userdata('datos_principales'));
                        $datos = $resp['id_user'] . '/' . md5($resp['id_user'] . $datos_principales['email']);

                        $data = array(
                            'email' => $datos_principales['email'],
                            'password' => $datos_principales['pass'],
                            'url' => site_url("gestion_usuario/validar_usuario_email/$datos"),
                            'view' => 'emails.nuevo_usuario'
                        );
                        $resp['send_email'] = $this->gestion_usuario->send_email_WS($data);
                    }
                    $this->session->set_userdata('registro', $resp);
                }
                break;
        }
        if ($this->form_validation->run() == FALSE) { //ERRORES VALIDACION
            if (isset($_POST)) $data['error'] = true;
            $this->load->view("gestion_usuario/registro_paso" . $this->paso_actual . "_view", $data);

        } else {
            switch ($this->paso_actual) {
                case 1:
                    $this->session->set_userdata('datos_principales', serialize($this->input->post()));
                    break;
                /* caso 2: SESSION ID_TAXPAYER EN FUNCION CALLBACK */
                case 3:
                    $this->session->set_userdata('cuentas_reportadas', $this->input->post('data_enviada'));
                    $cuentas = $this->session->userdata('cuentas');
                    if (!$cuentas['tiene_AE'])
                        $this->session->set_userdata('respuestas_correctas', 1); //ENVIA AL 5 DE UNA
                    break;
                case 4:
                    $this->session->set_userdata('respuestas_correctas', 1);
                    break;
            }
            redirect('gestion_usuario/registro/' . ($this->paso_actual + 1));
        }
        $this->load->view('footer');
    }

    function validar_usuario_email($id_user, $hash) {

        $data['valid_email'] = $this->gestion_usuario->validar_usuario_email($id_user, $hash);
        $this->load->view('gestion_usuario/registro_paso_email', $data);
        $this->load->view('footer');
    }

    function validarTelefono($tlf, $tipo){

        $patron = ($tipo == 'local') ? '/^0212-[\d]{3}(-[\d]{2}){2}$/' : '/^0(414|424|416|426|412)-[\d]{3}(-[\d]{2}){2}$/';

        if (!preg_match($patron, $tlf)) {
            $this->form_validation->set_message('validarTelefono', "Teléfono $tipo inválido");
            return FALSE;
        }
        return TRUE;
    }


    //METODOS CALLBACK
    function validacionesCuentaRenta($str) {
        if (strlen($str) != 16) {
            $this->form_validation->set_message('validacionesCuentaRenta', 'Tipo de Cuenta inv&aacute;lido');
            return FALSE;
        }
        $cuentas = $this->gestion_usuario->existe_contribuyente("cuentarenta", $str);
        if ($cuentas->usado) {
            $this->form_validation->set_message('validacionesCuentaRenta', 'Este Contribuyente ya posee una cuenta');
            return FALSE;
        }
        if (count(@$cuentas->cuentas) == 0) {
            $this->form_validation->set_message('validacionesCuentaRenta', 'Cuenta inexistente');
            return FALSE;
        }
        $this->session->set_userdata('cuentas', $cuentas);
        return TRUE;
    }

    function validacionesCuentaNueva($str) {
        if (strlen($str) != 9) {
            $this->form_validation->set_message('validacionesCuentaNueva', 'Tipo de Cuenta inv&aacute;lido');
            return FALSE;
        }
        $cuentas = $this->gestion_usuario->existe_contribuyente("cuentanueva", $str);

        #var_dump($cuentas, $this->gestion_usuario->errors); exit;

        if (@$cuentas->usado) {
            $this->form_validation->set_message('validacionesCuentaNueva', 'Este Contribuyente ya posee una cuenta');
            return FALSE;
        }
        if (count(@$cuentas->cuentas) == 0) {
            $this->form_validation->set_message('validacionesCuentaNueva', 'Cuenta inexistente');
            return FALSE;
        }
        $this->session->set_userdata('cuentas', (array)$cuentas);
        return TRUE;
    }

    function validaciones_password($str, $name = "la Contraseña") {

        if (strlen($str) < 6) {
            $this->form_validation->set_message('validaciones_password', "$name debe tener al menos 6 caracteres");
            return FALSE;
        }
        if (!preg_match('/^.*([a-zA-Z][\d]|[\d][a-zA-Z]).*$/', $str)) {
            $this->form_validation->set_message('validaciones_password', "$name debe tener combinaciones de letras y n&uacute;meros");
            return FALSE;
        }
        return TRUE;
    }

    function validar_rif($str) {
        if (!preg_match('/^[JG]-[\d]{8}-[\d]$/', $str)) {
            $this->form_validation->set_message('validar_rif', 'RIF Inv&aacute;lido');
            return FALSE;
        }
        return TRUE;
    }

    function validar_email($str) {
        if ($this->gestion_usuario->existe_email_usuario($str)) {
            $this->form_validation->set_message('validar_email', 'Correo electr&oacute;nico existente, use otra cuenta de correo');
            return FALSE;
        }

        return TRUE;
    }

    function validar_ult_planilla($str) {
        $ultima_planilla_pagada = $this->session->userdata('ultima_planilla_pagada');
        $cuentas = $this->session->userdata('cuentas');
        if (!in_array($str, $ultima_planilla_pagada->invoice)) {
            $this->form_validation->set_message('validar_ult_planilla', 'N&uacute;mero de planilla inv&aacute;lido');
            $this->session->set_userdata('ultima_planilla_pagada', $this->gestion_usuario->ultima_planilla_pagada($cuentas['id_taxpayer']));
            return FALSE;
        }

        return TRUE;
    }

    function validar_ult_declaracion($str) {
        $ultimo_numero_declaracion = $this->session->userdata('ultimo_numero_declaracion');
        $cuentas = $this->session->userdata('cuentas');
        if (!in_array($str, $ultimo_numero_declaracion->statement)) {
            $this->form_validation->set_message('validar_ult_declaracion', 'N&uacute;mero de declaracion inv&aacute;lido');
            $this->session->set_userdata('ultimo_numero_declaracion', $this->gestion_usuario->ultimo_numero_declaracion($cuentas['id_taxpayer']));
            return FALSE;
        }

        return TRUE;
    }

    function modificar_perfil()
    {
        if (! $this->session->userdata('usuario_appweb'))
            redirect(site_url());

        $this->output->set_output('');

        $header['arrayJS'] = array('gestion_usuario.js');
        $header['sidebar'] = 'menu/oficina_menu';

        $this->load->view('header', $header);

        $data['user'] = $this->session->userdata('usuario_appweb');

        if (isset($_POST) && ! empty($_POST))
        {
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><small>', '</small></div>');
            $this->form_validation->set_rules('nombres', '<strong>nombres</strong>', 'trim|required|alpha_dash');
            $this->form_validation->set_rules('apellidos', '<strong>apellidos</strong>', 'trim|required|alpha_dash');
            $this->form_validation->set_rules('local', '<strong>teléfono local</strong>', 'trim|required');
            $this->form_validation->set_rules('celular', '<strong>teléfono celular</strong>', 'trim|required');
            $this->form_validation->set_rules('my_password', '<strong>contraseña actual</strong>', 'trim');

            #$_POST['my_password'] = trim($_POST['my_password']);

            if (! empty($_POST['my_password']))
            {
                $this->form_validation->set_rules('my_password', '<strong>contraseña actual</strong>', 'callback_valid_my_password');
                $this->form_validation->set_rules('password', '<strong>contraseña</strong>', 'trim|required|callback_validaciones_password[El campo <strong>contraseña actual</strong>]');
                $this->form_validation->set_rules('password_confirm', '<strong>confirmar contraseña</strong>', 'trim|required|matches[password]');
            }

            if ($this->form_validation->run() === TRUE)
            {
                $update = $_POST;
                unset($update['my_password'], $update['password_confirm']);
                if (! empty($update['password']))
                {
                    $update['password'] = sha1($update['password']);
                }

                $resp = $this->gestion_usuario->update_user($data['user']->id, $update);

                if ($resp)
                {
                    $data['user'] = (object)array_merge((array)$data['user'], $update);
                    $this->session->set_userdata('usuario_appweb', $data['user']);

                    $this->messages->add_message("Usuario modificado con éxito", "success");
                    $dataEmail = array(
                        'password' => $_POST['password'],
                        'email' => $data['user']->email,
                        'view' => 'emails.actualizar_usuario'
                    );

                    if (! empty($update['password']))
                    {
                        if ($this->gestion_usuario->send_email_WS($dataEmail))
                        {
                            $this->messages->add_message("Se ha envíado un mensaje con su nueva contraseña a su correo electrónico", "success");
                        }
                        else
                        {
                            $this->messages->add_message("Error al enviar correo electrónico", "danger");
                        }
                    }

                    #d($this->gestion_usuario);
                    redirect(site_url());
                }
                else
                {
                    $this->messages->add_message("Error al intentar modificar usuario", "danger");
                }

                #d($update, $this->session->userdata('usuario_appweb'), $merge);
            }

        }

        #d($this->form_validation);

        $this->load->view('gestion_usuario/modificar_perfil', $data);

        $this->load->view('footer');
    }

    function valid_my_password($str)
    {
        if (sha1($str) !== $this->session->userdata('usuario_appweb')->password)
        {
            $this->form_validation->set_message('valid_my_password', 'El campo <strong>contraseña actual</strong> no corresponde con la de su sesión');
            return FALSE;
        }
        return TRUE;
    }

    public function probar_email() {

        $datos = '127/' . md5('127toronacii@gmail.com');

        $data = array(
            'email' => 'toronacii@gmail.com',
            'password' => 'abc123',
            'url' => site_url("gestion_usuario/validar_usuario_email/$datos"),
            'view' => 'emails.nuevo_usuario'
        );

        $resp = new stdClass();

        var_dump($this->gestion_usuario->send_email_WS($data));


    }

    function http()
    {
        $param = unserialize('a:3:{s:13:"datos_basicos";a:13:{s:12:"tipo_persona";s:7:"natural";s:6:"cedula";s:10:"19.387.920";s:3:"rif";s:0:"";s:12:"razon_social";s:0:"";s:7:"nombres";s:4:"JOSE";s:9:"apellidos";s:4:"TORO";s:9:"tlf_local";s:14:"0212-163-16-51";s:11:"tlf_celular";s:14:"0412-656-45-64";s:5:"email";s:22:"toronacii.ml@gmail.com";s:10:"conf_email";s:22:"toronacii.ml@gmail.com";s:4:"pass";s:6:"abc123";s:9:"conf_pass";s:6:"abc123";s:8:"contrato";s:1:"1";}s:11:"id_taxpayer";i:127442;s:18:"cuentas_reportadas";s:0:"";}');
        d(http_build_query($param, NULL, '&'));
    }

}

/* End of file gestion_usuario.php */
/* Location: ./application/controllers/gestion_usuario.php */
