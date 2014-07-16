<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Edocuenta extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        #$this->load->model('edocuenta_model', 'edocuenta');
        #$this->load->model('planilla_model', 'planilla');

        $this->load->model('api_model', 'planillas');

        $cuenta = $this->session->userdata('cuenta');
        $tipo = $this->session->userdata('edotipo');
        
        //parametros a pasar para generar el estado de cuenta------------
        $data['result'] = $this->planillas->infocontribuyente($cuenta);
		$data['id_tax'] = $data['result'][0]->tributo;
		$data['tipo']=$tipo;

        $data['clasificacion'] = NULL; #$this->planilla->buscar_clasificadores($data['result'][0]->tributo);

        if ($data['clasificacion'] == null)
            $data['clasificacion'] = $this->planillas->buscar_clasificadores2($data['result'][0]->tributo);

        

        $data['campos'] = $this->planillas->buscar_campos($data['result'][0]->tributo);
        $data['fecha'] = date('d/m/Y');

        #var_dump($data, $this->planilla); exit;
        
        $this->load->library('planilla.php');
        $planilla = new Planilla();
        $planilla->Generar_edocuenta($data);
    }

    function right($cuenta, $tipo) {

        $this->session->set_userdata('cuenta', $cuenta);
        $this->session->set_userdata('edotipo', $tipo);
        redirect(site_url('edocuenta'));
    }

    /* Esta Funcion crea la lista ordenada de debitos y creditos asi como calcula el total de la deuda. */

    public function CrearLista($debitos, $creditos) {
        ///Jose Manuel
        $matriz = array("date", "reference", "concept", "credit", "amount", "canceled", "expirydate");
        $matriz_aux = array("date", "credit", "amount", "canceled", "expirydate");
        $cont_matriz = 0;
        $cont_matriz_aux = 0;
        $debito_total = 0;
        $credito_total = 0;
        $total_amount = 0;
        $bandera = true;
        ////Calculamos los debitos y agrupamos los intereses

        foreach ($debitos As $deb) {
            if ($deb->reference_type != 3) { #NO SON INTERESES
                $matriz["date"][$cont_matriz] = $deb->application_date;
                $matriz["reference"][$cont_matriz] = $deb->reference_code;
                $matriz["concept"][$cont_matriz] = $deb->concept;
                //$matriz["credit"][$cont_matriz]=$deb->credit;
                $matriz["credit"][$cont_matriz] = false;
                $matriz["amount"][$cont_matriz] = $deb->amount;
                $matriz["canceled"][$cont_matriz] = $deb->canceled;
                $matriz["expirydate"][$cont_matriz] = $deb->expiry_date;
                $debito_total += $deb->amount;
                $cont_matriz++;
            } else { #INTERESES
                if ($bandera) {
                    $matriz_aux["date"][$cont_matriz_aux] = $deb->application_date;
                    //$matriz["credit"][$cont_matriz]=$deb->credit;
                    $matriz["credit"][$cont_matriz] = false;
                    $matriz_aux["amount"][$cont_matriz_aux] = $deb->amount;
                    $matriz_aux["canceled"][$cont_matriz_aux] = false;
                    $matriz_aux["expirydate"][$cont_matriz_aux] = $deb->expiry_date;
                    $bandera = false;
                } else {
                    if (strcmp($matriz_aux["date"][$cont_matriz_aux], $deb->application_date) == 0) {
                        $matriz_aux["amount"][$cont_matriz_aux] += $deb->amount;
                    } else {
                        $cont_matriz_aux++;
                        $matriz_aux["date"][$cont_matriz_aux] = $deb->application_date;
                        //$matriz["credit"][$cont_matriz]=$deb->credit;
                        $matriz["credit"][$cont_matriz] = false;
                        $matriz_aux["amount"][$cont_matriz_aux] = $deb->amount;
                        $matriz_aux["canceled"][$cont_matriz_aux] = false;
                        $matriz_aux["expirydate"][$cont_matriz_aux] = $deb->expiry_date;
                    }
                }
            }
        }
        if ($bandera == false)
            $cont_matriz_aux++;
        for ($i = 0; $i < $cont_matriz_aux; $i++) {
            $matriz["date"][$cont_matriz] = $matriz_aux["date"][$i];
            $matriz["reference"][$cont_matriz] = "";
            $matriz["concept"][$cont_matriz] = "Intereses de Mora";
            $matriz["credit"][$cont_matriz] = false;
            $matriz["amount"][$cont_matriz] = $matriz_aux["amount"][$i];
            $matriz["canceled"][$cont_matriz] = $matriz_aux["canceled"][$i];
            $matriz["expirydate"][$cont_matriz] = $matriz_aux["expirydate"][$i];
            $debito_total += $matriz_aux["amount"][$i];
            $cont_matriz++;
        }


        //Calculamos los creditos y se agrupan los pagos...
        $CI = & get_instance();
        $CI->load->library('fpdf/PDF_Code128');
        $CI->load->model('edocuenta_model', 'edocuenta');
        $referencia_vieja = "";
        
        #var_dump($creditos);exit;
        foreach ($creditos As $cre) {
            $payment = null;
            $payment = $CI->edocuenta->Buscar_pagos($cre->id);
            if ($payment == null) {
                if (@strcmp($cre->parent_transaction, "") == 0) {
                    if ($CI->edocuenta->Total_amount($cre->id) != null) {
                        $amount_new = $CI->edocuenta->Total_amount($cre->id);
                    } else {
                        $amount_new[0]->suma = 0;
                    }
                    if ($amount_new[0]->suma > 0) {
                        if ($cre->amount == 0) {
                            $matriz["amount"][$cont_matriz] = $amount_new[0]->suma;
                            @$credito_total += $amount_new[0]->suma;
                        } else {
                            $amount_new[0]->suma+=$cre->amount;
                            $matriz["amount"][$cont_matriz] = $amount_new[0]->suma;
                            @$credito_total += $amount_new[0]->suma;
                        }
                    } else {
                        $matriz["amount"][$cont_matriz] = $cre->amount;
                        @$credito_total += $cre->amount;
                    }
                    //	}
                    $matriz["date"][$cont_matriz] = $cre->application_date;
                    $matriz["reference"][$cont_matriz] = $cre->reference_code;
                    $matriz["concept"][$cont_matriz] = $cre->concept;
                    $matriz["credit"][$cont_matriz] = true;
                    $matriz["canceled"][$cont_matriz] = $cre->canceled;
                    $matriz["expirydate"][$cont_matriz] = $cre->expiry_date;
                    $cont_matriz++;
                }
                
            } else {///Es un credito asociado a un pago
                ////Cambiar la opcion que se va a mostrar del Status
                if ($payment[0]->reference_code != $referencia_vieja) {
                    $referencia_vieja = $payment[0]->reference_code;
                }
                $estatus = "";
                switch ($payment[0]->status) {
                    case '1': $estatus = "Por Compensar";
                        break;
                    case '2': $estatus = "Compensado";
                        break;
                    case '3': $estatus = "Rechazado";
                        break;
                }

                /////////////////////////////////
                $matriz["date"][$cont_matriz] = $payment[0]->application_date;
                $matriz["reference"][$cont_matriz] = $payment[0]->reference_code;
                $matriz["concept"][$cont_matriz] = "Pago de la Planilla #" . $payment[0]->number . " - " . $payment[0]->nombre . " (" . $estatus . ")";
                $matriz["credit"][$cont_matriz] = true;
                $matriz["amount"][$cont_matriz] = $payment[0]->amount;
                $matriz["canceled"][$cont_matriz] = $payment[0]->canceled;
                $matriz["expirydate"][$cont_matriz] = $payment[0]->application_date;
                $credito_total += $payment[0]->amount;
                $cont_matriz++;
            }
        }
        
        
        
        //////////////ORDENAMIENTO DE LA LISTA//////
        for ($i = 1; $i < $cont_matriz; $i++) {
            for ($j = 0; $j < $cont_matriz - $i; $j++) {
                if (strcmp($matriz["date"][$j], $matriz["date"][$j + 1]) > 0) {
                    //fecha
                    $k = $matriz["date"][$j + 1];
                    $matriz["date"][$j + 1] = $matriz["date"][$j];
                    $matriz["date"][$j] = $k;
                    //referencia
                    $k = $matriz["reference"][$j + 1];
                    $matriz["reference"][$j + 1] = $matriz["reference"][$j];
                    $matriz["reference"][$j] = $k;
                    //concept
                    $k = $matriz["concept"][$j + 1];
                    $matriz["concept"][$j + 1] = $matriz["concept"][$j];
                    $matriz["concept"][$j] = $k;
                    //amount
                    $k = $matriz["credit"][$j + 1];
                    $matriz["credit"][$j + 1] = $matriz["credit"][$j];
                    $matriz["credit"][$j] = $k;
                    //canceled
                    $k = $matriz["amount"][$j + 1];
                    $matriz["amount"][$j + 1] = $matriz["amount"][$j];
                    $matriz["amount"][$j] = $k;
                    //expirydate
                    $k = $matriz["expirydate"][$j + 1];
                    $matriz["expirydate"][$j + 1] = $matriz["expirydate"][$j];
                    $matriz["expirydate"][$j] = $k;
                }
            }
        }
        
        /*foreach ($matriz as $i=>$v){
            if (! is_int($i)){
                foreach ($v as $index => $vIndex){
                    $obj[$index]['date'] = $vIndex;
                    $obj[$index]['reference'] = $matriz['reference'][$index];
                    $obj[$index]['concept'] = $matriz['concept'][$index];
                    $obj[$index]['credit'] = $matriz['credit'][$index];
                    $obj[$index]['amount'] = $matriz['amount'][$index];
                    $obj[$index]['canceled'] = $matriz['canceled'][$index];
                    $obj[$index]['expirydate'] = $matriz['expirydate'][$index];
                }
            }
        }
        
        var_dump($obj);*/
        
        $total_amount = 0;
        $total_amount = $debito_total - $credito_total;
        $matriz["credito_total"] = $credito_total;
        $matriz["debito_total"] = $debito_total;
        $matriz["total_amount"] = $total_amount;
        $matriz["cantidad"] = $cont_matriz;
        return $matriz;
    }

}

/*End of file oficina_principal.php */
/* Location: ./application/controllers/oficina_principal.php */
