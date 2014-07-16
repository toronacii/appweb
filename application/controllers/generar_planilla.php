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

    /*function index() {

        #var_dump($_POST);

        if (isset($_POST['pdf'])) { /// GENERAR PDF PARA IMPRIMIR
            $this->session->set_userdata('pago', 'pdf');

            if ($_POST['tipo'] == 'tasa') {

                $this->session->set_userdata('tasa', $_POST['idtasa']);

                $this->genera_tasa();
            } else if ($_POST['tipo'] == 'impuesto') {
                $this->session->set_userdata('cargos', $_POST['idimpuesto']);
                $this->generar_impuesto();
            }
        } else if (isset($_POST['confirmpago'])) { // PAGAR EN LINEA DESDE VENTANA DE CONFIRMACION 
            //var_dump($_POST);
            if ($_POST['tipo'] == 'tasa') {

                $this->session->set_userdata('tasa', $_POST['idtasa']);
            } else if ($_POST['tipo'] == 'impuesto') {
                $this->session->set_userdata('cargos', $_POST['idimpuesto']);
            }
            
           
            if (strpos($_POST['monto'],',')){
                $_POST['monto'] = str_replace(',', '.',str_replace('.', '', $_POST['monto']));
            }
            
            // codigo para realizar el pago en linea
            $data['idtax'] = $_POST['idtax'];
            $data['tipo'] = $_POST['tipo'];
            $data['total_amount'] = $_POST['monto'];

            $this->load->view('header');
            $this->load->view('oficina_virtual/tipopago_view', $data);
            $this->load->view('footer');
        } else if (isset($_POST['pagar'])) { // PAGAR EN LINEA DESDE VENTANA DE SELECCION DE TIPO DE TARJETA tipopago_view.php
            $this->session->set_userdata('pago', 'linea');
            // para generar la planilla sin impresion
            $descTotal = 0;
            if ($_POST['tipo'] == 'tasa') {

                $valores = $this->genera_tasa();
                #var_dump($valores);
                $data['orderId'] = $valores['nplanilla'];
                $data['cod'] = $valores['cod'];
            } else if ($_POST['tipo'] == 'impuesto') {
                $valores = $this->generar_impuesto();
                $data['orderId'] = $valores['nplanilla'];
                $data['cod'] = $valores['cod'];
                $descTotal = $valores['descTotal'];
            } else {
                $data['orderId'] = $_POST['planilla'];
                $data['cod'] = $_POST['cod'];
            }// cuando se quiere pagar una planilla ya generada
//echo $_POST['monto'];
            $data['total'] = $_POST['monto'] - $descTotal;
            
            $data['monto_planilla'] = $data['total'];

            $data['id_tax'] = $_POST['tax'];

            $data['monto_appweb'] = $data['total'];

            $monto_planilla = $this->planilla->monto_planilla($data['orderId'], $data['total']);
            
            if ($monto_planilla == '')
                $monto_planilla = $data['total'];
            
            $dif = bcsub($monto_planilla,$data['total']); #RESTA DE FLOATS

            if ($dif != 0) {

                $sendEmail = array(
                    'emails' => 'toronacii@gmail.com, ricardo.salazar@alcaldiasucre.net',
                    'subject' => "DIFERENCIA DE PAGO EN LINEA ({$dif})",
                    'message' => "<h2>Error appweb</h2><br>Id tax : {$data['id_tax']} <br>Planilla : {$data['orderId']} <br>Monto correcto: $monto_planilla <br>Monto appweb: {$data['total']} <br><br><strong>Ojo: el monto que se envio a credicard es {$data['monto_planilla']}</strong>"
                );

                $this->querys->send_email_WS($sendEmail);
            }

            $data['total'] = $monto_planilla;

            $usuario = $this->session->userdata('usuario_appweb');
            $data['email'] = $usuario[0]->email;
            
            #var_dump($data); exit;
            $this->load->view('header');
            $this->load->view('oficina_virtual/espera_view', $data);
            $this->load->view('footer');
        }
    }

    function genera_tasa() {

        $pago = $this->session->userdata('pago');
        $idtasa = $this->session->userdata('tasa');


        if (($this->session->userdata('eventual') != '') && ($this->session->userdata('cuenta') == '')) {
            //echo "HOLA";exit;		
            $data['result'][0] = $this->session->userdata('eventual');

            //-----------Proceso para guardar en BD-------------------
            $fecha = date('Y-m-d');
            $login = 198;
            $tipoplanilla = 1;
            $tasa = $this->planilla->BuscarTasa($idtasa);
            $ramo = $tasa[0]->id_tax_type;
            $monto = $tasa[0]->tax_unit * UT;
            $concepto = $tasa[0]->name;
            $trib = '';
            $sujeto = '1000001';
            $nplanilla = $this->planilla->NPlanilla();
            $fechavenc = $this->planilla->fecha_vencimiento();
            $cod = $this->planilla->codval($fechavenc, $nplanilla, $monto);
            $info = $this->session->userdata('eventual');
            $resultado = $this->planilla->funcion_concurrencia_tasa($nplanilla, $trib, $fechavenc, $monto, $cod, $sujeto, $tipoplanilla, $idtasa);
            #var_dump($resultado);exit;
            // PASE DE PARAMETROS AL PDF
            $data['nplanilla'] = $nplanilla;
            $data['montoplanilla'] = number_format($monto, 2, '.', '');
            $data['cod'] = $cod;
            $data['fechavenc'] = substr($fechavenc, 8, 2) . "/" . substr($fechavenc, 5, 2) . "/" . substr($fechavenc, 0, 4);
            $data['fechaemision'] = substr($fecha, 8, 2) . "/" . substr($fecha, 5, 2) . "/" . substr($fecha, 0, 4);
            $data['login'] = 'WEB';
            $data['concepto'] = $concepto;
            $data['hechoimponible'] = $ramo;
            $data['tipo'] = 't';
            $data['type'] = 1;
            
            #var_dump($data); exit;

            //---------------------------
            $this->session->unset_userdata('eventual');
            $this->session->unset_userdata('tasa');
            $this->session->set_userdata('planilla_tasa', $data);
            redirect(site_url() . '/generar_planilla/genera_planilla');
            

            //------------------------------
        } else if (($this->session->userdata('eventual') == '') && ($this->session->userdata('cuenta') != '')) {

            #var_dump($resultado);
            $cuenta = $this->session->userdata('cuenta');
            $data['result'] = $this->planilla->infocontribuyente($cuenta);
            $tasa = $this->planilla->BuscarTasa($idtasa);
            $fecha = date('Y-m-d');
            $login = 198;
            $tipoplanilla = 1;
            $trib = $data['result'][0]->tributo;
            $ramo = $tasa[0]->id_tax_type;
            $monto = $tasa[0]->tax_unit * UT;
            $concepto = $tasa[0]->name;
            $sujeto = $data['result'][0]->sujeto;
            $nplanilla = $this->planilla->NPlanilla();
            $fechavenc = $this->planilla->fecha_vencimiento();
            $cod = $this->planilla->codval($fechavenc, $nplanilla, $monto);

            $resultado = $this->planilla->funcion_concurrencia_tasa($nplanilla, $trib, $fechavenc, $monto, $cod, $sujeto, $tipoplanilla, $idtasa);

            // PASE DE PARAMETROS AL PDF

            if (($resultado != '')) {

                if ($pago != 'linea') { // si el pago es en linea no hace falta mostrar el pdf	
                    $data['idtasa'] = $tasa[0]->id;
                    $data['nplanilla'] = $nplanilla;
                    $data['montoplanilla'] = number_format($monto, 2, '.', '');
                    $data['cod'] = $cod;
                    $data['fechavenc'] = substr($fechavenc, 8, 2) . "/" . substr($fechavenc, 5, 2) . "/" . substr($fechavenc, 0, 4);
                    $data['cantplanilla'] = $cantPlanilla->id;
                    $data['fechaemision'] = $fecha;
                    $data['asociados'] = $this->planilla->Buscar_asociados($trib, $nplanilla, 1);
                    $data['login'] = 'WEB';
                    $data['concepto'] = $concepto;
                    $data['hechoimponible'] = $ramo;
                    $data['tipo'] = 't';
                    $data['type'] = 1;

                    //----------------------------					
                    $this->session->unset_userdata('cuenta');
                    $this->session->unset_userdata('tasa');

                    $this->session->set_userdata('planilla_tasa', $data);
                    redirect(base_url() . 'index.php/generar_planilla/genera_planilla');
                } else {
                    $vtasa['nplanilla'] = $nplanilla;
                    $vtasa['cod'] = $cod;
                    return $vtasa;
                }
            }
        }
    }

    function generar_impuesto() {
        $pago = $this->session->userdata('pago');

        $ids_check = $this->session->userdata('cargos');

        $cuenta = $this->session->userdata('cuenta');
        $data['result'] = $this->planilla->infocontribuyente($cuenta);
        $fecha = date('Y-m-d');
        $tipoplanilla = 2;
        $trib = $data['result'][0]->tributo;
        $login = 198;
        $ramo = $data['result'][0]->hechoimponible;
        $sujeto = $data['result'][0]->sujeto;
        $nplanilla = $this->planilla->NPlanilla();
        $fechavenc = $this->planilla->fecha_vencimiento();


        if (@$ids_check) {
            $cargos_checked = $this->planilla->Buscar_Cargos_ids($ids_check);
            $descTotal = 0;

            foreach ($cargos_checked as $i => $objCargo) {

                //verifica el monto real si la transaccion esta pagada parcialmente
                if ($objCargo->debt_status == 2) {
                    $monto1 = @$this->planilla->cargosabonados($objCargo->id);
                    @$montoTotal+=$objCargo->amount - $monto1[0]->sum;
                }else
                    @$montoTotal+=$objCargo->amount;
                //-----------------------------------------------------------------		
                if ($ramo == 3 && $objCargo->code == 'A1' && substr($objCargo->application_date, 0, 4) == date('Y')) {
                    switch (date('m')) {
                        case '01': $desc = 30;
                            break;
                        case '02': $desc = 15;
                            break;
                        case '03': $desc = 5;
                            break;
                    }

                    if ($objCargo->debt_status == 2) {
                        $monto1 = @$this->planilla->cargosabonados($objCargo->id);
                        @$montocargo = $objCargo->amount - $monto1[0]->sum;
                    }else
                        @$montocargo = $objCargo->amount;


                    $descTotal += $montocargo * @$desc / 100;
                }

                if (($ramo == 2) && ($objCargo->code == 'A1') && (substr($objCargo->application_date, 0, 4) == date('Y'))) {
                    switch (date('m')) {
                        case '01': $desc = 20;
                            break;

                    }
                    @$cantTrimestres++;
                    if ($objCargo->debt_status == 2) {
                        $monto1 = @$this->planilla->cargosabonados($objCargo->id);
                        @$montocargo = $objCargo->amount - $monto1[0]->sum;
                    }else
                        @$montocargo = $objCargo->amount;


                    $descTotal += $montocargo * @$desc / 100;
                }
            }

            if (@$cantTrimestres < 4 && $ramo == 2)
                $descTotal = 0;

            $monto = $montoTotal - @$descTotal;

            $cod = $this->planilla->codval($fechavenc, $nplanilla, $monto);
            
            $cargos_checked = serialize($cargos_checked);

            if (@$descTotal == 0)
                $desc = 0;
            $monto = number_format($monto, 2, '.', '');
            
            
            $resultado = $this->planilla->funcion_concurrencia($nplanilla, $trib, $fechavenc, $monto, $cod, $sujeto, $tipoplanilla, $cargos_checked, $descTotal, $desc);


            if (($resultado != '')) {

                if ($pago != 'linea') { // si el pago es en linea no hace falta mostrar el pdf				
                    // PASE DE PARAMETROS AL PDF
                    $data['cargos'] = $cargos_checked;
                    $data['nplanilla'] = $nplanilla;
                    $data['montoplanilla'] = $monto;
                    $data['cod'] = $cod;
                    $data['fechavenc'] = substr($fechavenc, 8, 2) . "/" . substr($fechavenc, 5, 2) . "/" . substr($fechavenc, 0, 4);
                    $data['asociados'] = $this->planilla->Buscar_asociados($trib, $nplanilla, 2);
                    $data['fechaemision'] = substr($fecha, 8, 2) . "/" . substr($fecha, 5, 2) . "/" . substr($fecha, 0, 4);
                    $data['login'] = 'WEB';
                    $data['hechoimponible'] = $ramo;
                    $data['tipo'] = 'c';
                    $data['type'] = 2;
                    $data['descuento'] = $descTotal;
                     

                    //-----------------------------------------------
                    $this->session->unset_userdata('cuenta');
                    $this->session->unset_userdata('cargos');

                    $this->session->set_userdata('planilla', $data);
                    redirect(base_url() . 'index.php/generar_planilla/genera_planilla');
                } else {

                    $impuesto['nplanilla'] = $nplanilla;
                    $impuesto['cod'] = $cod;
                    $impuesto['descTotal'] = $descTotal;
                    return $impuesto;
                }
            }
        }
    }

    */
    
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

