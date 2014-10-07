<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Planilla {

    public function Generar($data) {

        extract($data); exit;
        $CI = & get_instance();
        $CI->load->library('fpdf/PDF_Code128');
        $CI->load->model('planilla_model');
        define('FPDF_FONTPATH', 'application/libraries/fpdf/font');
        $pdf = new PDF_Code128('P', 'mm', 'Letter');
        $pdf->SetMargins(9, 10);
        $pdf->SetAutoPageBreak(true, 0.2);

        $cantPerPage = 18;
        $totalAsoc = count(@$asociados);
        $fechaEmisionTotal = date('d/m/Y');
        $pagarAntesDe = $fechavenc;
        //$pagarAntesDe = '02/04/2012';
        if (@$descuento > 0) { //DESCUENTO
            $asociados[$totalAsoc]->emision_date = date('d/m/Y');
            $asociados[$totalAsoc]->concept = 'DESCUENTO POR PRONTO PAGO';
            $asociados[$totalAsoc]->expiry_date = '-';
            $asociados[$totalAsoc]->amount = '-' . round($descuento, 2);
        }
        if (($tipo == 'f') || ($tipo == 't')) { //TASA
            //$asociados[];	
            $asociados[0]->emision_date = $fechaemision;
            $asociados[0]->concept = utf8_decode($concepto);
            $asociados[0]->expiry_date = $fechavenc;
            $asociados[0]->amount = $montoplanilla;
        }
        $pageExtra = ($totalAsoc % $cantPerPage == 0) ? 0 : 1;
        $totalPages = (int) ($totalAsoc / $cantPerPage) + $pageExtra;
        foreach ($asociados as $iAsoc => $asociado) {
            (int) $index = $iAsoc / $cantPerPage;
            $objPerPage[$index][] = $asociado;
        }

        /* FIN DE GENERAR OBJETO PARA CADA PAGINA */



        foreach ($objPerPage as $iPag => $asociados) {

            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetLineWidth(0.5);
            $pdf->Image('css/img/cabeceraPDF2.png', 8, 7, 199);
            //$pdf->cell(200,0,'REGRESAR',0,0,'R');
            /* if(isset($this->session->userdata('usuario_appweb'))){$pdf->Link(140,0,50,50,site_url('oficina_principal'))}else{ */
            $pdf->Link(140, 0, 50, 50, "http://www.alcaldiamunicipiosucre.gov.ve/contenido/alcaldia/organigrama/direccion-de-rentas/"); //}

            /* DATOS CABECERA */
            $pdf->SetXY(43, 11);
            $pdf->cell(38, 6, '' . number_format($montoplanilla, 2, '.', '.') . '', 0, 0, 'C'); //MONTO TOTAL 
            $pdf->SetX(117);
            $pdf->cell(31, 7, '' . $pagarAntesDe . '', 0, 0, 'C'); // PAGAR ANTES DE 
            $pdf->SetXY(33, 20);
            $pdf->cell(10, 6, '' . $cod . '', 0, 0, 'C'); //CODIGO 
            $pdf->SetFont('Arial', '', 10);
            $code = $nplanilla;
            $pdf->Code128(95, 20, $code, 20, 4);
            $pdf->SetXY(94, 23);
            $pdf->cell(35, 6, '' . $nplanilla . '', 0, 0, 'L'); // N PLANILLA 
            $pdf->SetXY(9, 41);
            $pdf->SetFont('Arial', '', 9);
            $pdf->cell(120, 7, '' . $result[0]->firm_name . ' ' . $result[0]->corporate_name . '', 0, 0, 'L'); //NOMBRE / RAZN SOCIAL 
            $pdf->cell(33, 7, '' . $result[0]->rif . '', 0, 0, 'C'); // CI - RIF 
            $pdf->cell(44, 7, '' . @$result[0]->sujeto . '', 0, 0, 'C'); // N UNICO DE CONTRIBUYENTE 
            $pdf->SetXY(72, 53);
            $pdf->SetFont('Arial', '', 7.5);
            $pdf->Multicell(90, 4, '' . $result[0]->address . '', 0, 'L'); // DOMICILIO DECLARADO 
            $pdf->SetFont('Arial', '', 9);
            switch (@$hechoimponible) {
                case 2 : $hechoimponible = 'INMUEBLES URBANOS';
                    break;
                case 4 : $hechoimponible = 'PUBLICIDAD';
                    break;
                case 1 : $hechoimponible = 'ACTIVIDAD ECONOMICA';
                    break;
                case 3 : $hechoimponible = 'VEHICULO';
                    break;
                case 6 : $hechoimponible = 'ING. MUNICIPAL';
                    break;
                //case 6 : $hechoimponible= 'COMERCIO INFORMAL';break; 
                case 8 : $hechoimponible = 'TASA ADMINISTRATIVA';
                    break;
                case 5 : $hechoimponible = 'CATASTRO';
                    break;
                case 7 : $hechoimponible = 'REGISTRO';
                    break;
            }
            $pdf->SetXY(9, 53);
            $pdf->cell(62, 12, '' . $hechoimponible . '', 0, 0, 'C'); // TIPO TRIBUTO / TASA 
            $pdf->SetXY(162, 53);
            $pdf->cell(44, 4, '' . @$result[0]->rent_account . '', 0, 0, 'C'); //N CUENTA RENTA 
            $pdf->SetXY(162, $pdf->GetY() + 8);
            $pdf->cell(44, 5, '' . @$result[0]->tax_account_number . '', 0, 0, 'C'); // N CUENTA NUEVA 
            $pdf->SetXY(9, 76);
            $pdf->cell(27, 2, '' . '' . '', 'L', 0, 'C'); //FECHA DE LIQUIDACION
            $pdf->cell(113, 2, '' . '' . '', 'L', 0, 'L'); // CONCEPTO 
            $pdf->cell(26, 2, '' . '' . '', 'L', 0, 'C'); // FECHA DE VENCIMIENTO 
            $pdf->cell(31, 2, '' . '' . '', 'LR', 1, 'C'); // MONTO 
            $pdf->SetFont('Arial', '', 7.5);
            /* FIN DATOS CABECERA */

            $i = 0;
            //echo "<pre>";print_r($objPerPage);echo "</pre>";exit;
            //echo "<pre>";print_r($objPerPage);echo "</pre>";
            foreach ($asociados as $asociado) {

                $i++;

                if (($tipo == 'c') || ($tipo == 'r')) {
                    if ($asociado->expiry_date != '-') {//EVALUAR EL DESCUENTO
                        if ($type != 1) {

                            $concepto = $asociado->concept;
                            $fechaemi = substr($asociado->application_date, 8, 2) . "/" . substr($asociado->application_date, 5, 2) . "/" . substr($asociado->application_date, 0, 4);
                            $fechavenc = substr($asociado->expiry_date, 8, 2) . "/" . substr($asociado->expiry_date, 5, 2) . "/" . substr($asociado->expiry_date, 0, 4);

                            if ($asociado->debt_status == 2) {

                                #var_dump($CI->querys1->cargosabonados('ddxg'));

                                $monto1 = $CI->planilla_model->cargosabonados($asociado->id);

                                $montocargo = ($asociado->amount) - ($monto1[0]->sum);
                            }else
                                $montocargo = $asociado->amount;
                        }else {
                            $montocargo = $asociado->total_amount;
                            $concepto = $asociado->name;
                            $fechaemi = substr($asociado->emision_date, 8, 2) . "/" . substr($asociado->emision_date, 5, 2) . "/" . substr($asociado->emision_date, 0, 4);
                            $fechavenc = substr($asociado->expiry_date, 8, 2) . "/" . substr($asociado->expiry_date, 5, 2) . "/" . substr($asociado->expiry_date, 0, 4);
                        }
                    }
                }

                if (($tipo == 'f') || ($tipo == 't') || ($asociado->expiry_date == '-')) { // TASAS O DESCUENTO
                    $fechaemi = $asociado->emision_date;
                    $concepto = $asociado->concept;
                    $fechavenc = $asociado->expiry_date;
                    $montocargo = $asociado->amount;
                }
                /* CONCEPTOS */
                $pdf->cell(27, 4, '' . $fechaemi . '', 'L', 0, 'C'); // FECHA DE LIQUIDACION 
                $pdf->cell(113, 4, '' . utf8_decode($concepto) . '', 'L', 0, 'L'); // CONCEPTO
                $pdf->cell(26, 4, '' . $fechavenc . '', 'L', 0, 'C'); // FECHA DE VENCIMIENTO 
                $pdf->cell(31, 4, '' . number_format($montocargo, 2, ',', '.') . '', 'LR', 1, 'C');
            }
            for (; $i < $cantPerPage; $i++) {
                $pdf->cell(27, 4, '' . '' . '', 'L', 0, 'C'); // FECHA DE LIQUIDACION 
                $pdf->cell(113, 4, '' . '' . '', 'L', 0, 'L'); // CONCEPTO 
                $pdf->cell(26, 4, '' . '' . '', 'L', 0, 'C'); // FECHA DE VENCIMIENTO 
                $pdf->cell(31, 4, '' . '' . '', 'LR', 1, 'C'); // MONTO 
            }

            /* FIN CONCEPTOS */
            //$pdf->SetY(170); 
            //$pdf->cell(31,8,'',1,0,'C');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Image('css/img/pieTabla.png', $pdf->GetX(), $pdf->GetY(), 198);
            $pdf->SetX(175);
            $pdf->cell(31, 8, '' . number_format($montoplanilla, 2, ',', '.') . '', 0, 0, 'C'); // MONTO TOTAL

            if (@$reimprimir == 'p') {
                //marca de cancelado-----------
                $pdf->SetXY(29, 60);
                $pdf->SetFont('Arial', '', 25);
                $pdf->Rotate(20);
                $pdf->Text(0, 150, 'PLANILLA PAGADA - OFICINA VIRTUAL');
                $pdf->Rotate(0);
                $pdf->SetFont('Arial', '', 9);
                //marca de cancelado-----------------------------
            }
            $pdf->SetXY(9, 160);
            $pdf->Image('css/img/piePDF3a.png', $pdf->GetX(), $pdf->GetY(), 199, 67);
            $pdf->Image('css/img/piePDF2b.png', $pdf->GetX(), 231, 198, 41);
            $pdf->SetY(225);
            for ($i = 1; $i < 73; $i++) {
                $pdf->cell(2, 3, '', 'B', 0);
                $pdf->SetX($pdf->GetX() - 5);
            }/* DATOS DEL PIE */
            $pdf->SetXY(37, 222);
            $pdf->cell(25, 6, '' . $fechaemision . '', 0, 0, 'C'); // FECHA DE EMISION 
            $operador = 'WEB';
            $pdf->SetX(110);
            $pdf->cell(10, 6, $operador, 0, 0, 'C'); // OPERADOR
            $pdf->SetXY(199, 222);
            $pdf->cell(10, 6, $iPag + 1 . "/$totalPages", 0, 0, 'C'); // TOTAL PAGINAS
            $pdf->SetXY(42, 235);
            $pdf->cell(27, 5, '' . number_format($montoplanilla, 2, ',', '.') . '', 0, 0, 'C'); // MONTO TOTAL 
            $pdf->SetXY(42, 244);
            $pdf->cell(27, 5, '' . $pagarAntesDe . '', 0, 0, 'C'); // PAGAR ANTES DE 
            $pdf->SetXY(26, -28);
            $pdf->cell(10, 4, '' . $cod . '', 0, 0, 'C'); //CODIGO 
            $pdf->Code128(73, 251, $code, 20, 3);
            $pdf->SetXY(64, 254);
            $pdf->cell(37, 4, '' . $nplanilla . '', 0, 0, 'C'); // N PLANILLA 
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXy(55, -22);
            $pdf->MultiCell(59, 4, '' . $result[0]->firm_name . "-" . $result[0]->corporate_name, 0, 'L'); //NOMBRE / RAZON SOCIAL 
            $pdf->SetXY(30, -18);
            $pdf->cell(25, 4, '' . $result[0]->rif . '', 0, 0, 'C'); // CI / RIF 
            $pdf->SetXY(37, -12);
            $pdf->SetFont('Arial', '', 10);
            $pdf->cell(27, 5, '' . $fechaemision . '', 0, 0, 'L'); //FECHA DE EMISION 
            $pdf->SetX(108);
            $pdf->cell(25, 5, $operador, 0, 0, 'L'); // OPERADOR 
            $pdf->SetX(201);
            $pdf->cell(10, 5, $iPag + 1 . "/$totalPages", 0, 0, 'L'); // TOTAL PAGINAS
            /* FIN DATOS DEL PIE */
        }

        $pdf->Output('PLANILLA.pdf', 'I');
    }

    ///Comienzo de la funcion como tal.-.......///////////////////////////////////////////////////////
    function Generar_edocuenta($data) {
        extract($data);
        #d($data);
        $CI = & get_instance();
        $CI->load->library('fpdf/pdf');
        define('FPDF_FONTPATH', 'application/libraries/fpdf/font');
        $CI->load->model('api_model', 'planillas');

        if ($tipo == 1)
            $edocuenta = $CI->planillas->estado_cuenta($id_tax, date('Y-m-d'));
        else if ($tipo == 2)
            $edocuenta = $CI->planillas->estado_cuenta($id_tax);

        #var_dump($CI->planillas, $CI->planillas->estado_cuenta($id_tax, date('Y-m-d')));

        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetMargins(20, 30, 30);
        $pdf->Image('css/img/cabeceraedocuenta.jpg', 15, 5, 180, 20, 'JPG');
        $pdf->Link(140, 0, 50, 50, 'http://www.alcaldiamunicipiosucre.gov.ve/contenido/alcaldia/organigrama/direccion-de-rentas/');
        $pdf->Cell(0, 1, '', 0, 1);
        $pdf->Image('css/img/cabecera.PNG', 7, 25, 203, 50, 'PNG');

        //------------------------------------------------------------------------------------ 
        switch ($result[0]->hechoimponible) {
            case 2 : $hechoimponible = 'INMUEBLES URBANOS';
                break;
            case 4 : $hechoimponible = 'PUBLICIDAD';
                break;
            case 1 : $hechoimponible = 'ACTIVIDAD ECONOMICA';
                break;
            case 3 : $hechoimponible = 'VEHICULO';
                break;
        }


        $planilla = 'Estado_cuenta';
        //----------------------------------------------------------------------------------- 
        $pdf->SetXY(25, 33);
        $pdf->Cell(0, 5, '' . $result[0]->rif . '', 0, 0); //; 
        $pdf->SetXY(157, 33);
        $pdf->Cell(0, 5, utf8_decode($result[0]->sujeto), 0, 1, 'L'); //; 
        $pdf->SetX(55);
        $pdf->Cell(60, 5, utf8_decode($result[0]->firm_name), 0, 1, 'L'); // 
        $pdf->SetXY(50, 49);
        $pdf->Cell(0, 5, '' . $result[0]->tax_account_number . '', 0, 0); //; 
        $pdf->SetXY(140, 49);
        $pdf->Cell(0, 5, '' . $result[0]->rent_account . '', 0, 1); //; 
        $pdf->SetXY(45, 54);
        $pdf->Cell(0, 5, '' . $result[0]->initial_date . '', 0, 0); //; 
        $pdf->SetX(140);
        $pdf->Cell(0, 5, '' . $result[0]->registration_date . '', 0, 1); //; 
        $pdf->SetX(35);
        $pdf->MultiCell(150, 5, '' . $result[0]->address . '', 0, 'L');
        //Codigo Nuevo
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(14);
        $pdf->SetFillColor(200, 220, 255);
        // Se inserta la data de CLASIFICACIONES.
        $y = 65;   //79
        if ($clasificacion != null) {

            $pdf->SetXY(15, $y); // 
            $pdf->Cell(0, 10, 'CLASIFICACIONES', 0, 1);
            $pdf->Ln();
            $y+=8;
            $pdf->SetXY(15, $y);
            $pdf->MultiCell(15, 6, "CODIGO", 1, 'C', TRUE);
            $pdf->SetXY(30, $y);
            $pdf->Cell(85, 6, "NOMBRE", 1, 0, 'C', TRUE);
            $pdf->SetXY(115, $y); //posicion
            $pdf->Cell(20, 6, "ALICUOTA", 1, 0, 'C', TRUE);
            $pdf->SetXY(135, $y);
            $pdf->MultiCell(50, 6, "MINIMO TRIBUTABLE", 1, 'C', TRUE);

            $y+=6;
            foreach ($clasificacion As $clasificaciones) {
                if (strlen(trim($clasificaciones->nombre)) > 65) {
                    $tam = 10;
                    $tamc = 10;
                } else {
                    $tam = 5;
                    $tamc = 5;
                }
                $pdf->SetFont('Arial', '', 7);
                $pdf->SetXY(135, $y);
                $auxi = "UT";
                if (strlen($clasificaciones->minimo) == 0)
                    $auxi = "";
                $pdf->Cell(50, $tam, '' . $clasificaciones->minimo . ' ' . $auxi, 1, 'C');   // MINIMO TRIBUTABLE
                $pdf->SetXY(30, $y);
                $pdf->Cell(85, $tamc, utf8_decode($clasificaciones->nombre), 1, 'C');               // NOMBRE 
                $pdf->SetXY(115, $y);
                $auxi = "%";
                if (strlen($clasificaciones->aliquot) == 0)
                    $auxi = "";
                $pdf->Cell(20, $tam, '' . $clasificaciones->aliquot . ' ' . $auxi, 1, 'C');     // ALICUOTA
                $pdf->SetXY(15, $y);
                $pdf->Cell(15, $tam, '' . $clasificaciones->code . '', 1, 'C');     // CODIGO
                if (strlen(trim($clasificaciones->nombre)) > 48)
                    $y = $y + 10;
                else
                    $y = $y + 5;
                $pdf->Ln();
            }
        }
        ///////CAMPOS ADICIONALES///////////

        if ($campos != null && substr($result[0]->tax_account_number, 0, 2) != '03') {
            if ($y == 65)
                $y+=4;
            else
                $y+=5;
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(14);
            $pdf->SetFillColor(200, 220, 255);
            // Se inserta la data de CLASIFICACIONES.
            $pdf->SetXY(15, $y); // 
            $pdf->Cell(0, 10, 'CAMPOS ADICIONALES', 0, 1);
            $y+=10;
            $pdf->Ln();
            $pdf->SetXY(15, $y);
            $pdf->MultiCell(120, 6, "NOMBRE", 1, 'C', TRUE);
            $pdf->SetXY(135, $y);
            $pdf->Cell(50, 6, "VALOR", 1, 0, 'C', TRUE);
            $y+=6;
            foreach ($campos As $campo) {
                if (strlen(trim($campo->nombre)) > 90) {
                    $tam = 10;
                    $tamc = 5;
                } else {
                    $tam = 5;
                    $tamc = 5;
                }
                $pdf->SetFont('Arial', '', 7);
                $pdf->SetXY(15, $y);
                $pdf->Cell(120, $tamc, utf8_decode($campo->nombre), 1, 'C');               // NOMBRE 
                $pdf->SetXY(135, $y);
                switch ($campo->tipo) {
                    case '1': {
                            if (strcmp($campo->valor1, "") == 0)
                                $pdf->Cell(50, $tam, 'N/A', 1, 'C');     // VALOR1
                            else
                                $pdf->Cell(50, $tam, '' . $campo->valor1 . '', 1, 'C');
                            break;
                        }
                    case '2': {
                            if (strcmp($campo->valor2, "") == 0)
                                $pdf->Cell(50, $tam, 'N/A', 1, 'C');     // VALOR1
                            else
                                $pdf->Cell(50, $tam, '' . $campo->valor2 . '', 1, 'C');
                            break;
                        }
                    case '3': {
                            if (strcmp($campo->valor3, "") == 0)
                                $pdf->Cell(50, $tam, 'N/A', 1, 'C');     // VALOR1
                            else
                                $pdf->Cell(50, $tam, '' . $campo->valor3 . '', 1, 'C');
                            break;
                        }
                    case '4': {
                            if (strcmp($campo->valor4, "") == 0)
                                $pdf->Cell(50, $tam, 'N/A', 1, 'C');     // VALOR1
                            else
                                $pdf->Cell(50, $tam, '' . $campo->valor4 . '', 1, 'C');
                            break;
                        }
                    case '5': {
                            if (strcmp($campo->valor5, "") == 0)
                                $pdf->Cell(50, $tam, 'N/A', 1, 'C');     // VALOR1
                            else
                                $pdf->Cell(50, $tam, '' . $campo->valor5 . '', 1, 'C');
                            break;
                        }
                }
                if (strlen(trim($campo->nombre)) > 48)
                    $y = $y + 10;
                else
                    $y = $y + 5;
                $pdf->Ln();
            }
        }
        ////////////////////////////////////
        //FIn de los cambios
        $y+=3;
        $pdf->Ln();
        $pdf->SetXY(15, $y); //
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(0, 10, 'DETALLES DEL ESTADO DE CUENTA', 0, 1); //; 
        $y+=10;
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetXY(15, $y);
        $pdf->SetFillColor(200, 220, 255);
        //----table header 
        $pdf->MultiCell(21, 3, "FECHA LIQUIDACION", 1, 'C', TRUE);
        $pdf->SetXY(35, $y);
        $pdf->Cell(20, 6, "REFERENCIA", 1, 0, 'C', TRUE);
        $pdf->SetXY(55, $y); //posicion
        $pdf->Cell(70, 6, "CONCEPTO", 1, 0, 'C', TRUE);
        $pdf->SetXY(123, $y);
        $pdf->MultiCell(22, 3, "FECHA VENCIMIENTO", 1, 'C', TRUE);
        $pdf->SetXY(145, $y);
        $pdf->Cell(20, 6, "DEBITO (Bs)", 1, 0, 'C', TRUE);
        $pdf->SetXY(165, $y);
        $pdf->Cell(20, 6, "CREDITO (Bs)", 1, 0, 'C', TRUE);
        //----table body 
        $y+=6;
        $pdf->SetFont('Arial', '', 7);
        //$mat=$this->CrearLista($debito_interes,$credito_pago);

        #var_dump($edocuenta); exit;

        foreach ($edocuenta as $edo) {
            if ($y > 253) {
                $pdf->AddPage();
                $y = 10;
            }


            ////-------------------------------------------------------------------------------------------------               

            $credito = ($edo->credito) ? number_format($edo->credito, 2, '.', '.') : '-';
            $debito = ($edo->debito) ? number_format($edo->debito, 2, '.', '.') : '-';

            $fecha = ($edo->application_date) ? date('d-m-Y', strtotime($edo->application_date)) : '';
            $fechavenc = ($edo->expiry_date) ? date('d-m-Y', strtotime($edo->expiry_date)) : '';

            //MultiCell(ancho, alto, cadena, borde,alineacion, relleno) 
            if (strlen(trim($edo->concept)) > 48) {
                $tam = 10;
                $tamc = 5;
            } else {
                $tam = 5;
                $tamc = 5;
            }


            if($edo->canceled){
                $pdf->SetFillColor(215, 215, 215);
                $tacha=TRUE;
            }else {
                $tacha=FALSE;
            }

            $pdf->SetXY(15, $y);
            $pdf->MultiCell(20, $tam, $fecha, 1, 'C', $tacha);
            $pdf->SetXY(35, $y);
            $pdf->MultiCell(20, $tam, $edo->reference_code, 1, 'C', $tacha);
            // $pdf->MultiCell(20, $tam,"MIENTRAS",1,'C'); 
            $pdf->SetXY(55, $y);
            $pdf->MultiCell(68, $tamc, strtoupper(substr(utf8_decode($edo->concept), 0, 70)), 1, 'L', $tacha);
            $pdf->SetXY(123, $y);

            $pdf->MultiCell(22, $tam, $fechavenc, 1, 'C', $tacha);
            $pdf->SetXY(145, $y);
            $pdf->MultiCell(20, $tam, $debito, 1, 'C', $tacha);
            $pdf->SetXY(165, $y);
            $pdf->MultiCell(20, $tam, $credito, 1, 'C', $tacha);

            if (strlen(trim($edo->concept)) > 48)
                $y = $y + 10;
            else
                $y = $y + 5;
            $pdf->Ln();

            @$debito_total+=$edo->debito;
            @$credito_total+=$edo->credito;
        }
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetXY(35, $y + 5);
        $debi = number_format($debito_total, 2, '.', '.');
        $credi = number_format($credito_total, 2, '.', '.');
        $tota = number_format($debito_total - $credito_total, 2, '.', '.');
        $pdf->Cell(20, 6, "Total Creditos:", 1, 0, 'C', TRUE);
        $pdf->Cell(20, 6, "" . $credi . "", 1, 0, 'C', TRUE);
        $pdf->Cell(20, 6, "Total Debitos:", 1, 0, 'C', TRUE);
        $pdf->Cell(20, 6, "" . $debi . "", 1, 0, 'C', TRUE);
        $pdf->Cell(20, 6, "Total Deuda:", 1, 0, 'C', TRUE);
        $pdf->Cell(20, 6, "" . $tota . "", 1, 0, 'C', TRUE);

        //-----------footer 
        //$pdf->Footer(); 
        //------------------      

        $pdf->Output();
    }

    function generar_recibo_tramite($data) {

        extract($data);
        #var_dump($data); exit;

        $CI = & get_instance();
        $CI->load->library('fpdf/fpdf');
        $CI->load->model('api_model', 'tramites');
        define('FPDF_FONTPATH', 'application/libraries/fpdf/font');
        $pdf = new FPDF('L');
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetMargins(12, 10);
        $pdf->Image('css/img/cabeceratramite.png', 10, 10, 278);
        $pdf->Image('css/img/cuerpo_tramite.png', 10, 60, 278);
        $pdf->Image('css/img/pie_tramite.png', 10, 150, 278);

        $data_tramite = $CI->tramites->get_data_tramite($id_request);

        #dd($data_tramite, $data, $CI->tramites);

        $fecha = date('d/m/Y', strtotime($data_tramite->request_date));  //SI ES REIMPRIMIR

        $pdf->SetXY(225, 45);
        $pdf->Cell(30, 10, "FECHA:", 0, 0, 'R');
        $pdf->Cell(30, 10, $fecha , 0, 1, 'R');
        $pdf->Ln(15);       

        $pdf->Cell(91, 9, $data_tramite->request_code, 0, 0, 'C');
        $pdf->SetX($pdf->GetX() + 5);

        $pdf->Cell(179, 9, $data_tramite->request_type, 0, 1, 'C');

        $pdf->Ln(16);

        $pdf->Cell(91, 9, $taxpayer->rif, 0, 0, 'C');
        $pdf->SetX($pdf->GetX() + 5);
        $pdf->Cell(179, 9, $taxpayer->firm_name, 0, 1, 'C');

        $pdf->Ln(15);

        $pdf->Cell(91, 9, $data_tramite->tax_account_number, 0, 0, 'C');
        $pdf->SetX($pdf->GetX() + 5);
        $pdf->Cell(179, 9, $data_tramite->tax_type, 0, 1, 'C');

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(50, 178);
        $pdf->Cell(20, 5, $fecha, 0, 0, 'C');
        $pdf->SetX($pdf->GetX() + 80, 179);
        $pdf->Cell(10, 6, 'WEB', 0, 0, 'C');
        $pdf->SetX($pdf->GetX() + 100, 179);
        $pdf->Cell(10, 6, '1/1', 0, 0, 'C');
        $pdf->Output();
    }

    public function genera_DEI($data) {

        extract($data);
        $CI = & get_instance();
        $CI->load->library('fpdf/PDF_Code128');
        $CI->load->model('declaracion_model', 'declaracion');

        //var_dump($data); exit;

        $pdf = new PDF_Code128('P', 'mm', 'Letter');
        $cant_celdas = 14;
        $pdf->AddPage('L');
        $pdf->SetMargins(6, 5);
        $pdf->SetAutoPageBreak(true, 5);
        define('x_', 0);

        #CABECERA

        $pdf->Image('css/img/cabecera_declaracion.png', 5, 13, 269);
        $pdf->Image('css/img/pie_declaracion.png', 5, 145, 269);

        $pdf->SetTextColor(215, 215, 215);
        $pdf->Rotate(20);

        $data_planilla = $CI->declaracion->data_planilla_DEI2013($numero_declaracion);

        if (strtotime($data_planilla[0]->created_sttm) > strtotime('2012-10-31')) { //NOVIEMBRE
            $pdf->SetFont('Arial', '', 35);
            $pdf->Text(0, 150, utf8_decode('DECLARACIÓN EXTEMPORANEA'));
        } else {
            $pdf->SetFont('Arial', '', 40);
            $pdf->Text(15, 150, utf8_decode('DECLARACIÓN ESTIMADA'));
        }
        $pdf->Rotate(0);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 10);

        $pdf->setXY(6, 5);
        $pdf->Cell(0, 3, utf8_decode('PLANILLA DE DECLARACIÓN ESTIMADA MUNICIPIO SUCRE'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('DETERMINACIÓN DE IMPUESTO AÑO 2013'), 0, 0, 'C');

        $pdf->SetFont('Arial', '', 7);
        $pdf->SetY(22);
        $pdf->Cell(32, 4, 'DESDE', x_, 0, 'C');
        $pdf->Cell(34, 4, 'HASTA', x_, 0, 'C');


        $data_taxpayer = $CI->declaracion->datos_contribuyente($id_tax);
        $data_planilla = $CI->declaracion->data_planilla_DEI2013($numero_declaracion);

        #var_dump(array($data_planilla, $numero_declaracion, $data));

        $pdf->Cell(49, 4, $data_taxpayer->cuenta_renta, x_, 0, 'C'); #CUENTA RENTA
        $x = $pdf->GetX();
        $pdf->Multicell(58, 4, $data_taxpayer->razon_social, x_, 1); #RAZON SOCIAL

        $pdf->SetXY($x + 58, 22);



        $pdf->Cell(34, 7, $data_planilla[0]->form_number, x_, 1, 'C'); #NUMERO DE PLANILLA

        $pdf->SetY($pdf->GetY() - 3);

        $pdf->Cell(11, 4, '01', x_, 0, 'C'); #DESDE DIA
        $pdf->Cell(10, 4, '01', x_, 0, 'C'); #DESDE MES
        $pdf->Cell(11, 4, '2013', x_, 0, 'C'); #DESDE AÑO
        $pdf->Cell(12, 4, '31', x_, 0, 'C'); #HASTA DIA
        $pdf->Cell(10, 4, '12', x_, 0, 'C'); #HASTA MES
        $pdf->Cell(12, 4, '2013', x_, 0, 'C'); #HASTA AÑO
        $pdf->Cell(49, 4, $data_taxpayer->numero_cuenta, x_, 1, 'C'); #CUENTA NUEVA

        $pdf->SetY($pdf->GetY() + 3);
        $x = $pdf->GetX();

        $pdf->Multicell(58, 4, $data_taxpayer->nombre_comercial, x_, 1); #DENOMINACION COMERCIAL
        $pdf->SetXY($x + 66, 33);

        $pdf->Multicell(141, 4, $data_taxpayer->direccion, x_, 1); #DIRECCION

        $pdf->SetY(45);

        $pdf->Cell(45, 4, '', x_, 0, 'C'); #TIPO DE NEGOCIO
        $pdf->Cell(68, 4, $data_taxpayer->resp_legal, x_, 0, 'C'); #PROPIETARIO O RESPONSABLE LEGAL
        $pdf->Cell(39, 4, $data_taxpayer->ci_resp_legal, x_, 0, 'C'); #CI RESPONSABLE LEGAL
        $pdf->Cell(25, 4, $data_taxpayer->rif, x_, 0, 'C'); #RIF
        $pdf->Cell(30, 4, $data_taxpayer->local, x_, 0, 'C'); #TELEFONO
        $pdf->Cell(60, 4, $_SESSION['usuario_appweb'][0]->email, x_, 1, 'C'); #EMAIL

        $pdf->SetY($pdf->GetY() + 2);

        $pdf->Cell(14, 7, '', '1', 0, 'C');
        $pdf->Cell(18, 7, '', '1', 0, 'C');
        $pdf->Cell(59, 7, '', 'TRB', 0, 'L');
        $pdf->Cell(40, 7, '', 'TRB', 0, 'C');
        $pdf->Cell(17, 7, '', 'TRB', 0, 'C');
        $pdf->Cell(28, 7, '', 'TRB', 0, 'C');
        $pdf->Cell(33, 7, '', 'TRB', 0, 'C');
        $pdf->Cell(15, 7, '', 'TRB', 0, 'R');
        $pdf->Cell(16, 7, '', 'TRB', 0, 'R');
        $pdf->Cell(28, 7, '', 'TRB', 1, 'R');

        $tax_unit = $CI->declaracion->unidad_tributaria(2013);

        $pdf->SetFont('Arial', '', 8);

        foreach ($data_planilla as $i => $objPlanilla) {

            $name = utf8_decode($objPlanilla->name . "...");
            $monto = number_format($objPlanilla->monto, 2, ',', '.');
            $minimo = number_format($objPlanilla->minimun_taxable * $tax_unit->value, 2, ',', '.');
            $total = number_format($objPlanilla->caused_tax_form, 2, ',', '.');

            $pdf->Cell(14, 5, substr($objPlanilla->code, 0, 1), 'LBR', 0, 'C');   #GRUPO
            $pdf->Cell(18, 5, $objPlanilla->code, 'BR', 0, 'C');   #CODIGO
            $pdf->Cell(59, 5, $name, 'BR', 0, 'L'); #ACTIVIDADES
            $pdf->Cell(40, 5, $monto, 'BR', 0, 'R'); #INGRESOS BRUTOS
            $pdf->Cell(17, 5, $objPlanilla->aliquot, 'BR', 0, 'C'); #ALICUOTA
            $pdf->Cell(28, 5, $minimo, 'BR', 0, 'R'); #MINIMO TRIBUTARIO
            $pdf->Cell(33, 5, $total, 'BR', 0, 'R'); #IMPUESTO ANUAL
            $pdf->Cell(15, 5, '0 %', 'BR', 0, 'C'); #% REBAJA
            $pdf->Cell(16, 5, '0,00', 'BR', 0, 'R'); #MONTO DE REBAJA
            $pdf->Cell(28, 5, $total, 'BR', 1, 'R'); #IMPUESTO - REBAJA

            @$total_bruto += $objPlanilla->monto;
            @$total_impuesto += $objPlanilla->caused_tax_form;
        }


        for ($j = 1; $j < $cant_celdas - $i; $j++) {
            $pdf->Cell(14, 5, '', 'LBR', 0, 'C');   #GRUPO
            $pdf->Cell(18, 5, '', 'BR', 0, 'C');   #CODIGO
            $pdf->Cell(59, 5, '', 'BR', 0, 'L'); #ACTIVIDADES
            $pdf->Cell(40, 5, '', 'BR', 0, 'C'); #INGRESOS BRUTOS
            $pdf->Cell(17, 5, '', 'BR', 0, 'C'); #ALICUOTA
            $pdf->Cell(28, 5, '', 'BR', 0, 'C'); #MINIMO TRIBUTARIO
            $pdf->Cell(33, 5, '', 'BR', 0, 'C'); #IMPUESTO ANUAL
            $pdf->Cell(15, 5, '', 'BR', 0, 'C'); #% REBAJA
            $pdf->Cell(16, 5, '', 'BR', 0, 'R'); #MONTO DE REBAJA
            $pdf->Cell(28, 5, '', 'BR', 1, 'R'); #IMPUESTO - REBAJA
        }
        $pdf->Cell(91, 8, 'TOTAL INGRESOS BRUTOS DECLARADOS', 'LBR', 0, 'R');
        $pdf->Cell(40, 8, number_format($total_bruto, 2, ',', '.'), 'BR', 0, 'R');  #TOTAL INGRESOS BRUTOS

        $total_impuesto_format = number_format($total_impuesto, 2, ',', '.');

        $pdf->Cell(45, 8, 'TOTAL IMPUESTO ANUAL', 'BR', 0, 'C');
        $pdf->Cell(33, 8, $total_impuesto_format, 'BR', 0, 'R'); #TOTAL IMPUESTO ANUAL
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Multicell(15, 4, "TOTAL\nREBAJA", 'BR', 0);
        $pdf->SetXY($x + 15, $y);
        $pdf->Cell(16, 8, '0,00', 'BR', 1, 'R'); #TOTAL REBAJA


        $pdf->Cell(91, 8, '17. INGRESO DEFINITIVO 2011', 'LBR', 0, 'R');
        $pdf->Cell(40, 8, ($initial_date < '2012-01-01' || $sttm_def2011 > 0) ? number_format($total_declaracion, 2, ',', '.') : 'NO APLICA', 'BR', 1, 'R'); #INGRESO DEFINITIVO 2011

        $pdf->SetXY(-34, $pdf->GetY() + 2);
        $pdf->Cell(28, 7, $total_impuesto_format, x_, 1, 'R'); #TOTAL INGRESO - REBAJA

        $pdf->SetXY(-34, $pdf->GetY() + 2);
        $pdf->Cell(28, 8, number_format($total_impuesto / 4, 2, ',', '.'), x_, 1, 'R'); # TOTAL TRIMESTRE

        $pdf->SetXY(70, $pdf->GetY() + 15);

        $pdf->SetFont('Arial', '', 13);

        $pdf->Cell(58, 4, $data_planilla[0]->codval, x_, 0, 'C'); # CODIGO VALIDADOR
        $pdf->Cell(28, 4, $data_planilla[0]->form_number, x_, 1, 'C'); # NUMERO DE PLANILLA

        $pdf->Code128(127, $pdf->GetY(), $data_planilla[0]->form_number, 30, 8); #CODIGO DE BARRAS
        $pdf->SetX(70);
        $pdf->SetTextColor(215, 215, 215);
        $pdf->SetFont('Arial', '', 10);
        $fecha_liquidacion = date("d/m/Y", strtotime($data_planilla[0]->created_sttm));
        $pdf->Cell(58, 8, "LIQUIDADA WEB EL $fecha_liquidacion", x_, 1, 'C');
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetY($pdf->GetY() + 10);

        $pdf->Cell(190, 4, utf8_decode("PLANILLA DE DECLARACIÓN ESTIMADA MUNICIPIO SUCRE DETERMINACIÓN IMPUESTO ESTIMADO AÑO 2013"), x_, 0, 'C');
        $pdf->Cell(0, 4, "CONTRIBUYENTE", x_, 1, 'C');

        $pdf->Output('DEI.pdf', 'I');
    }

    public function genera_DDI($data) {

        extract($data);
        $CI = & get_instance();
        $CI->load->library('fpdf/PDF_Code128');
        $CI->load->model('declaracion_model', 'declaracion');

        //var_dump($data); exit;

        $pdf = new PDF_Code128('P', 'mm', 'Letter');
        $cant_celdas = 14;
        $pdf->AddPage('L');
        $pdf->SetMargins(6, 5);
        $pdf->SetAutoPageBreak(true, 5);
        define('x_', 0);

        #CABECERA

        $pdf->Image('css/img/cabecera_declaracion_DEF.png', 5, 13, 269);
        $pdf->Image('css/img/pie_declaracion_DEF.png', 5, 145, 269);

        $pdf->SetTextColor(215, 215, 215);
        $pdf->Rotate(20);

        $data_planilla = $CI->declaracion->data_planilla_DEI2013($id_sttm_form_def_2012);

        #var_dump($data_planilla, $id_sttm_form_def_2012); exit;

        if (strtotime($data_planilla[0]->created_sttm) > strtotime('2013-01-31')) { //FEBRERO
            $pdf->SetFont('Arial', '', 35);
            $pdf->Text(0, 150, utf8_decode('DECLARACIÓN EXTEMPORANEA'));
        } else {
            $pdf->SetFont('Arial', '', 40);
            $pdf->Text(15, 150, utf8_decode("DECLARACIÓN DEFINITIVA"));
        }
        $pdf->Rotate(0);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 10);

        $pdf->setXY(6, 5);
        $pdf->Cell(0, 3, utf8_decode('PLANILLA DE DECLARACIÓN DEFINITIVA MUNICIPIO SUCRE'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('DETERMINACIÓN DE IMPUESTO AÑO 2012'), 0, 0, 'C');

        $pdf->SetFont('Arial', '', 7);
        $pdf->SetY(22);
        $pdf->Cell(32, 4, 'DESDE', x_, 0, 'C');
        $pdf->Cell(34, 4, 'HASTA', x_, 0, 'C');


        $data_taxpayer = $CI->declaracion->datos_contribuyente($id_tax);
        $data_planilla = $CI->declaracion->data_planilla_DEI2013($id_sttm_form_def_2012);

        #var_dump($data_taxpayer, $id_sttm_form_def_2012);

        $pdf->Cell(49, 4, $data_taxpayer->cuenta_renta, x_, 0, 'C'); #CUENTA RENTA
        $x = $pdf->GetX();
        $pdf->Multicell(58, 4, utf8_decode($data_taxpayer->razon_social), x_, 1); #RAZON SOCIAL

        $pdf->SetXY($x + 58, 22);



        $pdf->Cell(34, 7, $data_planilla[0]->form_number, x_, 1, 'C'); #NUMERO DE PLANILLA

        $pdf->SetY($pdf->GetY() - 3);

        $pdf->Cell(11, 4, '01', x_, 0, 'C'); #DESDE DIA
        $pdf->Cell(10, 4, '01', x_, 0, 'C'); #DESDE MES
        $pdf->Cell(11, 4, '2012', x_, 0, 'C'); #DESDE AÑO
        $pdf->Cell(12, 4, '31', x_, 0, 'C'); #HASTA DIA
        $pdf->Cell(10, 4, '12', x_, 0, 'C'); #HASTA MES
        $pdf->Cell(12, 4, '2012', x_, 0, 'C'); #HASTA AÑO
        $pdf->Cell(49, 4, $data_taxpayer->numero_cuenta, x_, 1, 'C'); #CUENTA NUEVA

        $pdf->SetY($pdf->GetY() + 3);
        $x = $pdf->GetX();

        $pdf->Multicell(58, 4, utf8_decode($data_taxpayer->nombre_comercial), x_, 1); #DENOMINACION COMERCIAL
        $pdf->SetXY($x + 66, 33);

        $pdf->Multicell(141, 4, utf8_decode($data_taxpayer->direccion), x_, 1); #DIRECCION

        $pdf->SetY(45);

        $pdf->Cell(45, 4, '', x_, 0, 'C'); #TIPO DE NEGOCIO
        $pdf->Cell(68, 4, utf8_decode($data_taxpayer->resp_legal), x_, 0, 'C'); #PROPIETARIO O RESPONSABLE LEGAL
        $pdf->Cell(39, 4, $data_taxpayer->ci_resp_legal, x_, 0, 'C'); #CI RESPONSABLE LEGAL
        $pdf->Cell(25, 4, $data_taxpayer->rif, x_, 0, 'C'); #RIF
        $pdf->Cell(30, 4, $data_taxpayer->local, x_, 0, 'C'); #TELEFONO
        $pdf->Cell(60, 4, $_SESSION['usuario_appweb'][0]->email, x_, 1, 'C'); #EMAIL

        $pdf->SetY($pdf->GetY() + 2);

        $pdf->Cell(14, 7, '', '1', 0, 'C');
        $pdf->Cell(18, 7, '', '1', 0, 'C');
        $pdf->Cell(59, 7, '', 'TRB', 0, 'L');
        $pdf->Cell(40, 7, '', 'TRB', 0, 'C');
        $pdf->Cell(17, 7, '', 'TRB', 0, 'C');
        $pdf->Cell(28, 7, '', 'TRB', 0, 'C');
        $pdf->Cell(33, 7, '', 'TRB', 0, 'C');
        $pdf->Cell(15, 7, '', 'TRB', 0, 'R');
        $pdf->Cell(16, 7, '', 'TRB', 0, 'R');
        $pdf->Cell(28, 7, '', 'TRB', 1, 'R');

        $tax_unit = $CI->declaracion->unidad_tributaria(2012);

        $pdf->SetFont('Arial', '', 8);

        $rebajas = $CI->declaracion->rebajas($id_tax);

        #var_dump($rebajas, $data_planilla);

        foreach ($data_planilla as $i => $objPlanilla) {

            $authorized = ($objPlanilla->authorized == 't') ? "" : "+ ";
            $name = utf8_decode("$authorized{$objPlanilla->name}...");
            $monto = number_format($objPlanilla->monto, 2, ',', '.');
            $minimo = number_format($objPlanilla->minimun_taxable * $tax_unit->value, 2, ',', '.');

            $p_rebaja = '0 %';
            $rebaja = 0;
            $total_imp_reb = $objPlanilla->caused_tax_form;

            if (in_array($objPlanilla->code, $rebajas) && $objPlanilla->authorized == 't') { #REBAJA
                #EL CAUSED_TAX_FORM YA CONTEMPLA LA REBAJA DEL 50%
                $p_rebaja = '50 %';
                $rebaja = $objPlanilla->caused_tax_form;
                $total_imp_reb = $rebaja;
                #PARA QUE EL TOTAL NO CONTEMPLE LA REBAJA
                $objPlanilla->caused_tax_form *= 2;
            }
            $total = number_format($objPlanilla->caused_tax_form, 2, ',', '.');
            $pdf->Cell(14, 5, substr($objPlanilla->code, 0, 1), 'LBR', 0, 'C');   #GRUPO
            $pdf->Cell(18, 5, $objPlanilla->code, 'BR', 0, 'C');   #CODIGO
            $pdf->Cell(59, 5, $name, 'BR', 0, 'L'); #ACTIVIDADES
            $pdf->Cell(40, 5, $monto, 'BR', 0, 'R'); #INGRESOS BRUTOS
            $pdf->Cell(17, 5, $objPlanilla->aliquot, 'BR', 0, 'C'); #ALICUOTA
            $pdf->Cell(28, 5, $minimo, 'BR', 0, 'R'); #MINIMO TRIBUTARIO
            $pdf->Cell(33, 5, $total, 'BR', 0, 'R'); #IMPUESTO ANUAL
            $pdf->Cell(15, 5, $p_rebaja, 'BR', 0, 'C'); #% REBAJA

            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(16, 5, number_format(round($rebaja, 2), 2, ',', '.'), 'BR', 0, 'R'); #MONTO DE REBAJA
            $pdf->SetFont('Arial', '', 8);

            $pdf->Cell(28, 5, number_format(round($total_imp_reb, 2), 2, ',', '.'), 'BR', 1, 'R'); #IMPUESTO - REBAJA

            @$total_bruto += $objPlanilla->monto;
            @$total_rebaja += $rebaja;
            @$total_impuesto += $objPlanilla->caused_tax_form;
            @$total_impuesto_reb += $total_imp_reb;
        }


        for ($j = 1; $j < $cant_celdas - $i; $j++) {
            $pdf->Cell(14, 5, '', 'LBR', 0, 'C');   #GRUPO
            $pdf->Cell(18, 5, '', 'BR', 0, 'C');   #CODIGO
            $pdf->Cell(59, 5, '', 'BR', 0, 'L'); #ACTIVIDADES
            $pdf->Cell(40, 5, '', 'BR', 0, 'C'); #INGRESOS BRUTOS
            $pdf->Cell(17, 5, '', 'BR', 0, 'C'); #ALICUOTA
            $pdf->Cell(28, 5, '', 'BR', 0, 'C'); #MINIMO TRIBUTARIO
            $pdf->Cell(33, 5, '', 'BR', 0, 'C'); #IMPUESTO ANUAL
            $pdf->Cell(15, 5, '', 'BR', 0, 'C'); #% REBAJA
            $pdf->Cell(16, 5, '', 'BR', 0, 'R'); #MONTO DE REBAJA
            $pdf->Cell(28, 5, '', 'BR', 1, 'R'); #IMPUESTO - REBAJA
        }
        $pdf->Cell(91, 8, 'TOTAL INGRESOS BRUTOS DECLARADOS', 'LBR', 0, 'R');
        $pdf->Cell(40, 8, number_format($total_bruto, 2, ',', '.'), 'BR', 0, 'R');  #TOTAL INGRESOS BRUTOS

        $total_impuesto_format = number_format($total_impuesto, 2, ',', '.');

        $pdf->Cell(45, 8, 'TOTAL IMPUESTO ANUAL', 'BR', 0, 'C');
        $pdf->Cell(33, 8, $total_impuesto_format, 'BR', 0, 'R'); #TOTAL IMPUESTO ANUAL
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Multicell(15, 4, "TOTAL\nREBAJA", 'BR', 0);
        $pdf->SetXY($x + 15, $y);

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(16, 8, number_format(round($total_rebaja, 2), 2, ',', '.'), 'BR', 1, 'R'); #TOTAL REBAJA
        $pdf->SetFont('Arial', '', 8);

        #var_dump($data);

        $pdf->Cell(91, 8, '17. IMPUESTO ESTIMADO 2012', 'LBR', 0, 'R');
        $pdf->Cell(40, 8, ($initial_date < '2012-01-01' || $tot_est_2012 > 0) ? number_format($tot_est_2012, 2, ',', '.') : 'NO APLICA', 'BR', 1, 'R'); #INGRESO DEFINITIVO 2011

        $pdf->SetXY(-34, $pdf->GetY() + 2);
        $pdf->Cell(28, 7, number_format(round($total_impuesto_reb, 2), 2, ',', '.'), x_, 1, 'R'); #TOTAL INGRESO - REBAJA

        $pdf->SetXY(-34, $pdf->GetY() + 2);
        $pdf->Cell(28, 8, number_format($total_impuesto_reb - $tot_est_2012, 2, ',', '.'), x_, 1, 'R'); # TOTAL COMPLEMENTO

        $pdf->SetXY(70, $pdf->GetY() + 15);

        $pdf->SetFont('Arial', '', 13);

        $pdf->Cell(58, 4, $data_planilla[0]->codval, x_, 0, 'C'); # CODIGO VALIDADOR
        $pdf->Cell(28, 4, $data_planilla[0]->form_number, x_, 1, 'C'); # NUMERO DE PLANILLA

        $pdf->Code128(127, $pdf->GetY(), $data_planilla[0]->form_number, 30, 8); #CODIGO DE BARRAS
        $pdf->SetX(70);
        $pdf->SetTextColor(215, 215, 215);
        $pdf->SetFont('Arial', '', 10);
        $fecha_liquidacion = date("d/m/Y", strtotime($data_planilla[0]->created_sttm));
        $pdf->Cell(58, 8, "LIQUIDADA WEB EL $fecha_liquidacion", x_, 1, 'C');
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetY($pdf->GetY() + 10);

        $pdf->Cell(190, 4, utf8_decode("PLANILLA DE DECLARACIÓN DEFINITIVA MUNICIPIO SUCRE DETERMINACIÓN IMPUESTO ESTIMADO AÑO 2012"), x_, 0, 'C');
        $pdf->Cell(0, 4, "CONTRIBUYENTE", x_, 1, 'C');

        $pdf->Output("DDI_{$CI->uri->segment(3)}.pdf", 'I');
    }

    public function genera_calc_PU($data) {
        extract($data);
        $CI = & get_instance();
        $CI->load->library('fpdf/PDF_Code128');

        #var_dump($data); exit;

        $pdf = new PDF_Code128('P', 'mm', 'Letter');
        $cant_celdas = 14;
        $pdf->AddPage('L');
        $pdf->SetAutoPageBreak(true, 5);
        define('x_', 0);

        #CABECERA

        $pdf->SetLeftMargin(15);
        $pdf->SetRightMargin(22);
        $pdf->Image('css/img/cabeceracalculadora.png', 15, 5, 245, 30, 'PNG');

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 9);

        #DATA

        $pdf->SetY(40);

        $pdf->SetDrawColor(170, 170, 170);

        $pdf->Cell(95, 8, utf8_decode('DESCRIPCIÓN'), 'LT', 0, 'C');
        $pdf->Cell(30, 8, utf8_decode('ANCHO'), 'LT', 0, 'C');
        $pdf->Cell(30, 8, utf8_decode('LARGO'), 'LT', 0, 'C');
        $pdf->Cell(30, 8, utf8_decode('DIAS'), 'LT', 0, 'C');
        $pdf->Cell(30, 8, utf8_decode('CANTIDAD'), 'LT', 0, 'C');
        $pdf->Cell(30, 8, utf8_decode('TOTAL'), 'LTR', 1, 'C');

        $i = 0;


        foreach ($data as $index => $valores) {
            if (is_array($valores)) {

                parse_str($valores['NOMBRE']); // CREA LAS VARIABLES $name Y $cant_unit
                $cant_unit = ($cant_unit == 1) ? $cant_unit = "" : " x $cant_unit";
                ($i++ % 2 == 0) ? $pdf->SetFillColor(224, 224, 224) : $pdf->SetFillColor(255, 255, 255);


                $pdf->Cell(95, 8, utf8_decode($name), 'L', 0, 'L', true);
                $pdf->Cell(30, 8, isset($valores['ANCHO']) ? $valores['ANCHO'] : 'NO APLICA', 'L', 0, 'C', true);
                $pdf->Cell(30, 8, isset($valores['LARGO']) ? $valores['LARGO'] : 'NO APLICA', 'L', 0, 'C', true);
                $pdf->Cell(30, 8, isset($valores['DIAS']) ? $valores['DIAS'] : 'NO APLICA', 'L', 0, 'C', true);
                $pdf->Cell(30, 8, isset($valores['UNIDAD']) ? $valores['UNIDAD'] . $cant_unit : 'NO APLICA', 'L', 0, 'C', true);
                $pdf->Cell(30, 8, isset($valores['TOTAL']) ? $valores['TOTAL'] : 'NO APLICA', 'LR', 1, 'C', true);
            }
        }

        #echo $i;

        for ($j = 1; $i <= 14 && $j <= 3; $i++, $j++) {

            ($i % 2 == 0) ? $pdf->SetFillColor(224, 224, 224) : $pdf->SetFillColor(255, 255, 255);

            $pdf->Cell(95, 8, '', 'LB', 0, 'L', true);
            $pdf->Cell(30, 8, '', 'LB', 0, 'C', true);
            $pdf->Cell(30, 8, '', 'LB', 0, 'C', true);
            $pdf->Cell(30, 8, '', 'LB', 0, 'C', true);
            $pdf->Cell(30, 8, '', 'LB', 0, 'C', true);
            $pdf->Cell(30, 8, '', 'LRB', 1, 'C', true);
        }


        $pdf->SetFillColor(210, 210, 210);

        $pdf->SetX(170);

        $pdf->Cell(60, 8, 'SUBTOTAL', 'LT', 0, 'R', true);
        $pdf->Cell(30, 8, $data['subtotal'], 'TR', 1, 'C', true);

        $pdf->SetX(170);

        $pdf->Cell(60, 8, 'IMPUESTO ESPECIAL 25%', 'L', 0, 'R', true);
        $pdf->Cell(30, 8, $data['imp_esp'], 'R', 1, 'C', true);

        $pdf->SetX(170);

        $pdf->Cell(60, 8, 'TOTAL GENERAL', 'LB', 0, 'R', true);
        $pdf->Cell(30, 8, $data['total_general'], 'BR', 1, 'C', true);

        $pdf->Output("Calculadorap.pdf", 'I');
    }

    public function mostrar_unificada($id_invoice) {

        $CI = & get_instance();
        $CI->load->library('fpdf/PDF_Code128');
        $CI->load->model('api_model', 'planillas');

        $pdf = new PDF_Code128('P', 'mm', 'Letter');
        $pdf->SetMargins(9, 10);
        $pdf->SetAutoPageBreak(true, 0.2);

        define('x_', 0);
        define('PER_PAGE_', 18);


        $data_planilla = $CI->planillas->get_detail_planilla_unificada($id_invoice);
        $header_planilla = $CI->planillas->get_header_planilla_unificada($id_invoice);

        #for ($x = 1; $x < 100; $x++) $data_planilla[] = $data_planilla[rand (0, 1)];

        $pageExtra = (count($data_planilla) % PER_PAGE_ == 0) ? 0 : 1;
        $totalPages = (int) (count($data_planilla) / PER_PAGE_) + $pageExtra;
        foreach ($data_planilla as $iCargo => $cargo) {
            $index = (int) ($iCargo / PER_PAGE_);
            $objPerPage[$index][] = $cargo;
        }

        #var_dump($data_planilla, $header_planilla, $CI->planillas); exit;
        #var_dump($objPerPage);

        foreach ($objPerPage as $iPag => $cargos) {
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetLineWidth(0.5);
            $pdf->Image('css/img/cabeceraPDFUnificada.png', 8, 7, 199);
            #$pdf->Link(140, 0, 50, 50, "http://www.alcaldiamunicipiosucre.gov.ve/contenido/alcaldia/organigrama/direccion-de-rentas/");

            #CABECERA
            $pdf->SetXY(43, 11);
            $pdf->cell(38, 6, number_format(round($header_planilla->total_amount, 2), 2, ',', '.'), x_, 0, 'C'); //MONTO TOTAL 
            $pdf->SetX(117);
            $pdf->cell(31, 7, date('d/m/Y', strtotime($header_planilla->expiry_date)), x_, 0, 'C'); // PAGAR ANTES DE 
            $pdf->SetXY(33, 20);
            $pdf->cell(10, 6, $header_planilla->validation_code, x_, 0, 'C'); //CODIGO 
            $pdf->SetFont('Arial', '', 10);
            $pdf->Code128(95, 20, $header_planilla->invoice_number, 20, 4);
            $pdf->SetXY(94, 23);
            $pdf->cell(35, 6, $header_planilla->invoice_number, x_, 0, 'L'); // N PLANILLA 
            $pdf->SetXY(9, 41);
            $pdf->SetFont('Arial', '', 9);
            $pdf->cell(120, 7, "{$header_planilla->firm_name}", 0, 0, 'L'); //NOMBRE / RAZN SOCIAL 
            $pdf->cell(33, 7, $header_planilla->rif, x_, 0, 'C'); // CI - RIF 
            $pdf->cell(44, 7, $header_planilla->id_taxpayer, x_, 0, 'C'); // N UNICO DE CONTRIBUYENTE 
            $pdf->SetXY(72, 53);
            $pdf->SetFont('Arial', '', 7.5);
            $pdf->Multicell(90, 4, $header_planilla->address, x_, 'L'); // DOMICILIO DECLARADO 
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetXY(9, 53);
            $pdf->cell(62, 12, "PLANILLA UNIFICADA", x_, 0, 'C'); // TIPO TRIBUTO / TASA 
            $pdf->SetXY(162, 53);
            $pdf->cell(44, 4, '', x_, 0, 'C'); //N CUENTA RENTA 
            $pdf->SetXY(162, $pdf->GetY() + 8);
            $pdf->cell(44, 5, '', x_, 0, 'C'); // N CUENTA NUEVA 
            $pdf->SetXY(9, 77);

            $i = 0;
            #CUERPO
            foreach ($cargos as $cargo) {

                $pdf->cell(29.5, 4, $cargo->tax_account_number, 'L', 0, 'C'); // CUENTA NUEVA
                $pdf->cell(67.3, 4, $cargo->name, 'L', 0, 'L'); // RAMO
                $pdf->cell(30, 4, number_format(round($cargo->amount, 2), 2, ',', '.'), 'L', 0, 'C'); // MONTO PARCIAL
                $pdf->cell(7.6, 4, $cargo->discount_porcent, 'L', 0, 'C'); // PORCENTAJE DE DESCUENTO
                $pdf->cell(23.6, 4, number_format(round($cargo->discount, 2), 2, ',', '.'), 'L', 0, 'C'); // MONTO DE DESCUENTO
                $monto_con_descuento = $cargo->amount - $cargo->discount;
                $pdf->cell(39.2, 4, number_format(round($monto_con_descuento, 2), 2, ',', '.'), 'LR', 1, 'C'); // TOTAL MONTO A PAGAR POR CUENTA
                $i++;
            }
            for (; $i < PER_PAGE_; $i++) {
                $pdf->cell(29.5, 4, "", 'L', 0, 'C'); // NUMERO DE CUENTA NUEVA 
                $pdf->cell(67.3, 4, "", 'L', 0, 'L'); // RAMO 
                $pdf->cell(30, 4, "", 'L', 0, 'C'); // MONTO PARCIAL 
                $pdf->cell(7.6, 4, "", 'L', 0, 'C'); // PORCENTAJE DE DESCUENTO 
                $pdf->cell(23.6, 4, "", 'L', 0, 'C'); // MONTO DE DESCUENTO 
                $pdf->cell(39.2, 4, "", 'LR', 1, 'C'); // TOTAL MONTO A PAGAR POR CUENTA 
            }

            $pdf->SetFont('Arial', '', 9);
            $pdf->Image('css/img/pieTablaUnificada.png', $pdf->GetX(), $pdf->GetY(), 198);
            $pdf->SetX(167);
            $pdf->cell(39, 8, number_format(round($header_planilla->total_amount, 2), 2, ',', '.'), x_, 0, 'C'); // MONTO TOTAL
            #PIE
            $pdf->SetXY(9, 160);
            $pdf->Image('css/img/piePDF3a.png', $pdf->GetX(), $pdf->GetY(), 199, 67);
            $pdf->Image('css/img/piePDF2b.png', $pdf->GetX(), 231, 198, 41);
            $pdf->SetY(225);
            for ($i = 1; $i < 73; $i++) {
                $pdf->cell(2, 3, '', 'B', 0);
                $pdf->SetX($pdf->GetX() - 5);
            }
            $pdf->SetXY(37, 222);
            $pdf->cell(25, 6, date('d/m/Y', strtotime($header_planilla->emision_date)), x_, 0, 'C'); // FECHA DE EMISION 
            $pdf->SetX(110);
            $pdf->cell(10, 6, "WEB", x_, 0, 'C'); // OPERADOR
            $pdf->SetXY(199, 222);
            $pdf->cell(10, 6, $iPag + 1 . "/$totalPages", x_, 0, 'C'); // TOTAL PAGINAS
            $pdf->SetXY(42, 235);
            $pdf->cell(27, 5, number_format(round($header_planilla->total_amount, 2), 2, ',', '.'), x_, 0, 'C'); // MONTO TOTAL 
            $pdf->SetXY(42, 244);
            $pdf->cell(27, 5, date('d/m/Y', strtotime($header_planilla->expiry_date)), x_, 0, 'C'); // PAGAR ANTES DE 
            $pdf->SetXY(26, -28);
            $pdf->cell(10, 4, $header_planilla->validation_code, x_, 0, 'C'); //CODIGO 
            $pdf->Code128(73, 251, $header_planilla->invoice_number, 20, 3);
            $pdf->SetXY(64, 254);
            $pdf->cell(37, 4, $header_planilla->invoice_number, x_, 0, 'C'); // N PLANILLA 
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXy(55, -22);
            $pdf->MultiCell(59, 4, '' . "{$header_planilla->firm_name}", 0, 'L'); //NOMBRE / RAZON SOCIAL 
            $pdf->SetXY(30, -18);
            $pdf->cell(25, 4, $header_planilla->rif, 0, 0, 'C'); // CI / RIF 
            $pdf->SetXY(37, -12);
            $pdf->SetFont('Arial', '', 10);
            $pdf->cell(27, 5, date('d/m/Y', strtotime($header_planilla->emision_date)), x_, 0, 'L'); //FECHA DE EMISION 
            $pdf->SetX(108);
            $pdf->cell(25, 5, "WEB", 0, 0, 'L'); // OPERADOR 
            $pdf->SetX(201);
            $pdf->cell(10, 5, $iPag + 1 . "/$totalPages", x_, 0, 'L'); // TOTAL PAGINAS*/
        }
        $pdf->Output('planilla_unificada.pdf', 'I');
    }

    public function show_statement($data_planilla){

        $CI = & get_instance();
        $CI->load->library('fpdf/PDF_Code128');
        $CI->load->model('api_model', 'declaraciones');

        #var_dump($data_planilla); exit;

        $pdf = new PDF_Code128('P', 'mm', 'Letter');
        $cant_celdas = 14;
        $pdf->AddPage('L');
        $pdf->SetMargins(6, 5);
        $pdf->SetAutoPageBreak(true, 5);
        define('x_', 0);

        $rebajas = array();

        if ($data_planilla[0]->type == 'TRUE'){ #DEFINITIVA
            $dirImageHeader = "css/img/cabecera_declaracion_DEF.png";
            $dirImageFooter = "css/img/pie_declaracion_DEF.png";
            $name_sttm = "DEFINITIVA";
            $year_tax_unit = $data_planilla[0]->fiscal_year;
            if (strtotime($data_planilla[0]->statement_date) < strtotime('2013-09-27') && $data_planilla[0]->fiscal_year == 2012)
                $rebajas = $CI->declaraciones->get_rebajas($data_planilla[0]->id_tax);
            $textSttmOld = "17. IMPUESTO ESTIMADO {$data_planilla[0]->fiscal_year}";

        }else if ($data_planilla[0]->type == 'FALSE'){ #ESTIMADA
            $dirImageHeader = "css/img/cabecera_declaracion.png";
            $dirImageFooter = "css/img/pie_declaracion.png";
            $name_sttm = "ESTIMADA";
            $year_tax_unit = $data_planilla[0]->fiscal_year - 1;
            $textSttmOld = "17. INGRESOS DEFINITIVOS ". ($data_planilla[0]->fiscal_year - 2);
        }

        #CABECERA

        $pdf->Image($dirImageHeader, 5, 13, 269);
        $pdf->Image($dirImageFooter, 5, 145, 269);

        $pdf->SetTextColor(215, 215, 215);
        $pdf->Rotate(20);

        #var_dump($data_planilla);exit;

        if ($data_planilla[0]->extemp == 't') { //NOVIEMBRE
            $pdf->SetFont('Arial', '', 35);
            $pdf->Text(0, 150, utf8_decode("DECLARACIÓN EXTEMPORÁNEA"));
        } else {
            $pdf->SetFont('Arial', '', 40);
            $pdf->Text(15, 150, utf8_decode("DECLARACION $name_sttm"));
        }

        if ($data_planilla[0]->change_audit == 't'){ #MONTOS MODIFICADOS POR AUDITORIA
            $pdf->SetFont('Arial', '', 20);
            $pdf->Text(40, 160, utf8_decode("(MODIFICADA POR AUDITORÍA FISCAL)"));
        }

        $pdf->Rotate(0);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 10);

        $pdf->setXY(6, 5);
        $pdf->Cell(0, 3, utf8_decode("PLANILLA DE DECLARACIÓN $name_sttm MUNICIPIO SUCRE"), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode("DETERMINACIÓN DE IMPUESTO AÑO {$data_planilla[0]->fiscal_year}"), 0, 0, 'C');

        $pdf->SetFont('Arial', '', 7);
        $pdf->SetY(22);
        $pdf->Cell(32, 4, 'DESDE', x_, 0, 'C');
        $pdf->Cell(34, 4, 'HASTA', x_, 0, 'C');

        $data_taxpayer = $CI->declaraciones->datos_taxpayer($data_planilla[0]->id_tax);

        #var_dump($data_taxpayer, $CI->declaraciones); exit;

        $pdf->Cell(49, 4, $data_taxpayer->cuenta_renta, x_, 0, 'C'); #CUENTA RENTA
        $x = $pdf->GetX();
        $pdf->Multicell(58, 4, utf8_decode($data_taxpayer->razon_social), x_, 1); #RAZON SOCIAL

        $pdf->SetXY($x + 58, 22);



        $pdf->Cell(34, 7, $data_planilla[0]->form_number, x_, 1, 'C'); #NUMERO DE PLANILLA

        $pdf->SetY($pdf->GetY() - 3);

        $pdf->Cell(11, 4, '01', x_, 0, 'C'); #DESDE DIA
        $pdf->Cell(10, 4, '01', x_, 0, 'C'); #DESDE MES
        $pdf->Cell(11, 4, $data_planilla[0]->fiscal_year, x_, 0, 'C'); #DESDE AÑO
        $pdf->Cell(12, 4, '31', x_, 0, 'C'); #HASTA DIA
        $pdf->Cell(10, 4, '12', x_, 0, 'C'); #HASTA MES
        $pdf->Cell(12, 4, $data_planilla[0]->fiscal_year, x_, 0, 'C'); #HASTA AÑO
        $pdf->Cell(49, 4, $data_taxpayer->numero_cuenta, x_, 1, 'C'); #CUENTA NUEVA

        $pdf->SetY($pdf->GetY() + 3);
        $x = $pdf->GetX();

        $pdf->Multicell(58, 4, utf8_decode($data_taxpayer->razon_social), x_, 1); #DENOMINACION COMERCIAL
        $pdf->SetXY($x + 66, 33);

        $pdf->Multicell(141, 4, utf8_decode($data_taxpayer->direccion), x_, 1); #DIRECCION

        $pdf->SetY(45);

        $pdf->Cell(45, 4, '', x_, 0, 'C'); #TIPO DE NEGOCIO
        $pdf->Cell(68, 4, utf8_decode($data_taxpayer->resp_legal), x_, 0, 'C'); #PROPIETARIO O RESPONSABLE LEGAL
        $pdf->Cell(39, 4, $data_taxpayer->ci_resp_legal, x_, 0, 'C'); #CI RESPONSABLE LEGAL
        $pdf->Cell(25, 4, $data_taxpayer->rif, x_, 0, 'C'); #RIF
        $pdf->Cell(30, 4, $data_taxpayer->local, x_, 0, 'C'); #TELEFONO
        $pdf->Cell(60, 4, '', x_, 1, 'C'); #EMAIL

        $pdf->SetY($pdf->GetY() + 2);

        $pdf->Cell(14, 7, '', '1', 0, 'C');
        $pdf->Cell(18, 7, '', '1', 0, 'C');
        $pdf->Cell(59, 7, '', 'TRB', 0, 'L');
        $pdf->Cell(40, 7, '', 'TRB', 0, 'C');
        $pdf->Cell(17, 7, '', 'TRB', 0, 'C');
        $pdf->Cell(28, 7, '', 'TRB', 0, 'C');
        $pdf->Cell(33, 7, '', 'TRB', 0, 'C');
        $pdf->Cell(15, 7, '', 'TRB', 0, 'R');
        $pdf->Cell(16, 7, '', 'TRB', 0, 'R');
        $pdf->Cell(28, 7, '', 'TRB', 1, 'R');

        $tax_unit = $CI->declaraciones->get_tax_unit($year_tax_unit);

        $pdf->SetFont('Arial', '', 8);

        #var_dump($rebajas, $data_planilla);

        foreach ($data_planilla as $i => $objPlanilla) {

            $permised = ($objPlanilla->permised == 't') ? "" : "+ ";
            $name = utf8_decode("$permised{$objPlanilla->name}...");
            $income = number_format($objPlanilla->income, 2, ',', '.');
            $minimo = number_format($objPlanilla->minimun_taxable * $tax_unit->value, 2, ',', '.');

            $p_rebaja = '0 %';
            $rebaja = 0;
            $total_imp_reb = $objPlanilla->caused_tax;

            if (in_array($objPlanilla->code, $rebajas) && $objPlanilla->permised == 't') { #REBAJA
                #EL CAUSED_TAX YA CONTEMPLA LA REBAJA DEL 50%
                $p_rebaja = '50 %';
                $rebaja = $objPlanilla->caused_tax;
                $total_imp_reb = $rebaja;
                #PARA QUE EL TOTAL NO CONTEMPLE LA REBAJA
                $objPlanilla->caused_tax *= 2;
            }

            $total = number_format($objPlanilla->caused_tax, 2, ',', '.');

            $pdf->Cell(14, 5, substr($objPlanilla->code, 0, 1), 'LBR', 0, 'C');   #GRUPO
            $pdf->Cell(18, 5, $objPlanilla->code, 'BR', 0, 'C');   #CODIGO
            $pdf->Cell(59, 5, $name, 'BR', 0, 'L'); #ACTIVIDADES
            $pdf->Cell(40, 5, $income, 'BR', 0, 'R'); #INGRESOS BRUTOS
            $pdf->Cell(17, 5, $objPlanilla->aliquot, 'BR', 0, 'C'); #ALICUOTA
            $pdf->Cell(28, 5, $minimo, 'BR', 0, 'R'); #MINIMO TRIBUTARIO
            $pdf->Cell(33, 5, $total, 'BR', 0, 'R'); #IMPUESTO ANUAL
            $pdf->Cell(15, 5, $p_rebaja, 'BR', 0, 'C'); #% REBAJA

            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(16, 5, number_format(round($rebaja, 2), 2, ',', '.'), 'BR', 0, 'R'); #MONTO DE REBAJA
            $pdf->SetFont('Arial', '', 8);

            $pdf->Cell(28, 5, number_format(round($total_imp_reb, 2), 2, ',', '.'), 'BR', 1, 'R'); #IMPUESTO - REBAJA

            @$total_bruto += $objPlanilla->income;
            @$total_rebaja += $rebaja;
            @$total_impuesto += $objPlanilla->caused_tax;
            @$total_impuesto_reb += $total_imp_reb;
        }


        for ($j = 1; $j < $cant_celdas - $i; $j++) {
            $pdf->Cell(14, 5, '', 'LBR', 0, 'C');   #GRUPO
            $pdf->Cell(18, 5, '', 'BR', 0, 'C');   #CODIGO
            $pdf->Cell(59, 5, '', 'BR', 0, 'L'); #ACTIVIDADES
            $pdf->Cell(40, 5, '', 'BR', 0, 'C'); #INGRESOS BRUTOS
            $pdf->Cell(17, 5, '', 'BR', 0, 'C'); #ALICUOTA
            $pdf->Cell(28, 5, '', 'BR', 0, 'C'); #MINIMO TRIBUTARIO
            $pdf->Cell(33, 5, '', 'BR', 0, 'C'); #IMPUESTO ANUAL
            $pdf->Cell(15, 5, '', 'BR', 0, 'C'); #% REBAJA
            $pdf->Cell(16, 5, '', 'BR', 0, 'R'); #MONTO DE REBAJA
            $pdf->Cell(28, 5, '', 'BR', 1, 'R'); #IMPUESTO - REBAJA
        }

        ######################################

        $pdf->Cell(91, 8, 'TOTAL INGRESOS BRUTOS DECLARADOS', 'LBR', 0, 'R');
        $pdf->Cell(40, 8, number_format($total_bruto, 2, ',', '.'), 'BR', 0, 'R');  #TOTAL INGRESOS BRUTOS

        $pdf->Cell(45, 8, 'TOTAL IMPUESTO ANUAL', 'BR', 0, 'C');
        $pdf->Cell(33, 8, number_format(round($total_impuesto_reb, 2), 2, ',', '.'), 'BR', 0, 'R'); #TOTAL IMPUESTO ANUAL
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Multicell(15, 4, "TOTAL\nREBAJA", 'BR', 0);
        $pdf->SetXY($x + 15, $y);

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(16, 8, number_format(round($total_rebaja, 2), 2, ',', '.'), 'BR', 1, 'R'); #TOTAL REBAJA
        $pdf->SetFont('Arial', '', 8);

        $total_old = $CI->declaraciones->get_total_sttm($data_planilla[0]->id_tax, $data_planilla[0]->type, $data_planilla[0]->fiscal_year);

        #var_dump(array($data_planilla[0]->id_tax, $data_planilla[0]->type, $data_planilla[0]->fiscal_year));exit;
        
        $pdf->Cell(91, 8, $textSttmOld, 'LBR', 0, 'R');
        $pdf->Cell(40, 8,  number_format(round($total_old, 2), 2, ',', '.'), 'BR', ($data_planilla[0]->id_tax_discount == ''), 'R'); #INGRESO ANTERIOR


        if ($data_planilla[0]->id_tax_discount > 0){ #DESCUENTO POR ARTICULO 219

            $pdf->Cell(45, 8,  'DESCUENTO POR ART. 219', 'BR', 0, 'C');
            $pdf->Cell(33, 8,  number_format(round($data_planilla[0]->amount_discount, 2), 2, ',', '.'), 'BR', 0, 'R'); #MONTO DESCUENTO POR ARTICULO 219
            $pdf->Cell(31, 8,  'SUBTOTAL', 'BR', 0, 'C');
            $pdf->Cell(28, 8,  number_format(round($total_impuesto_reb, 2), 2, ',', '.'), 'BRT', 1, 'R');
            $total_impuesto_reb -= $data_planilla[0]->amount_discount;

        }
        
        $pdf->SetXY(-34, $pdf->GetY() + 2);
        $pdf->Cell(28, 7, number_format(round($total_impuesto_reb, 2), 2, ',', '.'), x_, 1, 'R'); #TOTAL INGRESO - REBAJA
        
        if ($data_planilla[0]->type == 'TRUE'){ #COMPLEMENTO
            $comp_aforo = round($total_impuesto_reb - $total_old, 2);
        }else{ # AFOROS TRIMESTRALES
            $comp_aforo = round($total_impuesto_reb / 4, 2);
        }
        
        
        $pdf->SetXY(-34, $pdf->GetY() + 2);
        $pdf->Cell(28, 8, number_format($comp_aforo, 2, ',', '.') , x_, 1, 'R'); # TOTAL COMPLEMENTO

        $pdf->SetXY(70, $pdf->GetY() + 15);

        $pdf->SetFont('Arial', '', 13);
        
        $pdf->Cell(58, 4, $data_planilla[0]->codval, x_, 0, 'C'); # CODIGO VALIDADOR
        $pdf->Cell(28, 4, $data_planilla[0]->form_number, x_, 1, 'C'); # NUMERO DE PLANILLA

        $pdf->Code128(127, $pdf->GetY(), $data_planilla[0]->form_number, 30, 8); #CODIGO DE BARRAS
        $pdf->SetX(70);
        $pdf->SetTextColor(215, 215, 215);
        $pdf->SetFont('Arial', '', 10);

        $fecha_liquidacion = date("d/m/Y", strtotime($data_planilla[0]->statement_date));

        $liquidada = (empty($data_planilla[0]->codval)) ? "LIQUIDADA EL $fecha_liquidacion" : "LIQUIDADA WEB EL $fecha_liquidacion";
        
        $pdf->Cell(58, 8, $liquidada, x_, 1, 'C');
        
        $pdf->SetTextColor(0, 0, 0);

        @$pdf->SetY($pdf->GetY() + 10);

        $pdf->Cell(190, 4, utf8_decode("PLANILLA DE DECLARACIÓN $name_sttm MUNICIPIO SUCRE DETERMINACIÓN IMPUESTO AÑO {$data_planilla[0]->fiscal_year}"), x_, 0, 'C');
        $pdf->Cell(0, 4, "CONTRIBUYENTE", x_, 1, 'C');

        $pdf->Output("DDI_{$CI->uri->segment(3)}.pdf", 'I');
    }

    public function show_invoice($id_invoice){
        
        #d($id_invoice); 

        $CI = & get_instance();
        $CI->load->library('fpdf/PDF_Code128');
        $CI->load->model('api_model', 'planillas');
        define('FPDF_FONTPATH', 'application/libraries/fpdf/font');
        $pdf = new PDF_Code128('P', 'mm', 'Letter');
        $pdf->SetMargins(9, 10);
        $pdf->SetAutoPageBreak(true, 0.2);

        $data = (array)$CI->planillas->data_pdf_invoice($id_invoice);
        extract($data);
        
        #d($data);

        #PLANILLA DE TASA DESDE CONTRIBUYENTE EVENTUAL

        if ($session = $CI->session->userdata('metadata'))
        {
            $session = (object)$session;
            $metadata->firm_name = $session->razon_social;
            $metadata->rif = "{$session->tipo_doc}-{$session->rif}";
            $metadata->address = $session->direccion;
        }


        #var_dump($data, $CI->planillas, $CI->session->userdata('metadata')); 

        #var_dump($data);

        define('_x', 0);
        define('_CANT_PER_PAGE_', 18);
        
        if ($metadata->discount_amount) { //DESCUENTO

            $discount = new stdClass();
            
            $discount->application_date = $metadata->emision_date;
            $discount->concept = "DESCUENTO DEL $metadata->discount_percent% POR PRONTO PAGO";
            $discount->expiry_date = null;
            $discount->amount = -1 * $metadata->discount_amount;

            $cargos[] = $discount;
        }

        $totalCargos = count($cargos);

        $pageExtra = ($totalCargos % _CANT_PER_PAGE_ == 0) ? 0 : 1;
        $totalPages = (int) ($totalCargos / _CANT_PER_PAGE_) + $pageExtra;
        
        foreach ($cargos as $iCarg => $cargo) {
            (int) $page = $iCarg / _CANT_PER_PAGE_;
            $CargosPerPage[$page + 1][] = $cargo;
        }

        foreach ($CargosPerPage as $page => $cargos){

            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetLineWidth(0.5);
            $pdf->Image('css/img/cabeceraPDF2.png', 8, 7, 199);

            $pdf->SetXY(43, 11);
            $pdf->cell(38, 6, number_format($metadata->total_amount, 2, ',', '.'), _x, 0, 'C'); # MONTO TOTAL
            $pdf->SetX(117);
            $pdf->cell(31, 7, date('d/m/Y', strtotime($metadata->expiry_date)), _x, 0, 'C'); # PAGAR ANTES DE 
            $pdf->SetXY(33, 20);
            $pdf->cell(10, 6, $metadata->validation_code, _x, 0, 'C'); # CODIGO 
            $pdf->SetFont('Arial', '', 10);
            $pdf->Code128(95, 20, $metadata->invoice_number, 20, 4);
            $pdf->SetXY(94, 23);
            $pdf->cell(35, 6, $metadata->invoice_number, _x, 0, 'L'); # N PLANILLA 
            $pdf->SetXY(9, 41);
            $pdf->SetFont('Arial', '', 9);
            $pdf->cell(120, 7, $metadata->firm_name, _x, 0, 'L'); # NOMBRE / RAZN SOCIAL 
            $pdf->cell(33, 7, $metadata->rif, _x, 0, 'C'); # CI - RIF 
            $pdf->cell(44, 7, $metadata->id_taxpayer, _x, 0, 'C'); # N UNICO DE CONTRIBUYENTE 
            $pdf->SetXY(72, 53);
            $pdf->SetFont('Arial', '', 7.5);
            $pdf->Multicell(90, 4, $metadata->address, 0, 'L'); # DOMICILIO DECLARADO 
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetXY(9, 53);
            $pdf->cell(62, 12, $metadata->tax_type, _x, 0, 'C'); # TIPO TRIBUTO / TASA 
            $pdf->SetXY(162, 53);
            $pdf->cell(44, 4, $metadata->rent_account, _x, 0, 'C');  # N CUENTA RENTA 
            $pdf->SetXY(162, $pdf->GetY() + 8);
            $pdf->cell(44, 5, $metadata->tax_account_number, _x, 0, 'C');  # N CUENTA NUEVA 
            $pdf->SetXY(9, 76);
            $pdf->cell(27, 2, "", "LR", 0, 'C');  # FECHA DE LIQUIDACION
            $pdf->cell(113, 2, "", "R", 0, 'L'); # CONCEPTO 
            $pdf->cell(26, 2, "", "R", 0, 'C');  # FECHA DE VENCIMIENTO 
            $pdf->cell(31, 2, "", "R", 1, 'C');  # MONTO 
            $pdf->SetFont('Arial', '', 7.5);

            $i = 0;
            $amount_per_page = 0;

            foreach ($cargos as $cargo){
                $expiry_date = ($cargo->expiry_date) ? date('d/m/Y', strtotime($cargo->expiry_date)) : date('d/m/Y', strtotime($metadata->expiry_date));

                $pdf->cell(27,  4, date('d/m/Y', strtotime($cargo->application_date)), "LR", 0, 'C');  # FECHA DE LIQUIDACION
                $pdf->cell(113, 4, utf8_decode($cargo->concept), "R", 0, 'L'); # CONCEPTO 
                $pdf->cell(26,  4, $expiry_date, "R", 0, 'C');  # FECHA DE VENCIMIENTO 
                $pdf->cell(31,  4, number_format($cargo->amount, 2, ',', '.'), "R", 1, 'C');  # MONTO 
                $amount_per_page += $cargo->amount;
                $i++;
            }

            for (; $i < _CANT_PER_PAGE_; $i++) {
                $pdf->cell(27, 4, "", "LR", 0, 'C');  # FECHA DE LIQUIDACION
                $pdf->cell(113, 4, "", "R", 0, 'L'); # CONCEPTO 
                $pdf->cell(26, 4, "", "R", 0, 'C');  # FECHA DE VENCIMIENTO 
                $pdf->cell(31, 4, "", "R", 1, 'C');  # MONTO 
            }

            $pdf->SetFont('Arial', '', 9);
            $pdf->Image('css/img/pieTabla.png', $pdf->GetX(), $pdf->GetY(), 198);
            $pdf->SetX(175);
            $pdf->cell(31, 8, number_format($amount_per_page, 2, ',', '.'), _x, 0, 'C'); # MONTO TOTAL

            #d($data);
            if (in_array($metadata->status, [4, 6, 7])) {
                //marca de pagado-----------
                $pdf->SetXY(29, 60);
                $pdf->SetTextColor(215, 215, 215);

                $pdf->SetFont('Arial', '', 25);
                $pdf->Rotate(20);
                $pdf->Text(0, 150, 'PLANILLA PAGADA - OFICINA VIRTUAL');
                $pdf->Rotate(0);
                $pdf->SetFont('Arial', '', 9);

                $pdf->SetTextColor(0, 0, 0);
                //marca de pagado-----------------------------
            }
            $pdf->SetXY(9, 160);
            $pdf->Image('css/img/piePDF3a.png', $pdf->GetX(), $pdf->GetY(), 199, 67);
            $pdf->Image('css/img/piePDF2b.png', $pdf->GetX(), 231, 198, 41);
            $pdf->SetY(225);
            for ($i = 1; $i < 73; $i++) {
                $pdf->cell(2, 3, '', 'B', 0);
                $pdf->SetX($pdf->GetX() - 5);
            }
            $pdf->SetXY(37, 222);
            $pdf->cell(25, 6, date('d/m/Y', strtotime($metadata->emision_date)), _x, 0, 'C'); # FECHA DE EMISION 
            $pdf->SetX(110);
            $pdf->cell(10, 6,"WEB", _x, 0, 'C'); # OPERADOR
            $pdf->SetXY(199, 222);
            $pdf->cell(10, 6, $page . "/$totalPages", _x, 0, 'C'); # TOTAL PAGINAS
            $pdf->SetXY(42, 235);
            $pdf->cell(27, 5, number_format($metadata->total_amount, 2, ',', '.'), _x, 0, 'C'); # MONTO TOTAL 
            $pdf->SetXY(42, 244);
            $pdf->cell(27, 5, date('d/m/Y', strtotime($metadata->expiry_date)), _x, 0, 'C'); # PAGAR ANTES DE 
            $pdf->SetXY(26, -28);
            $pdf->cell(10, 4, $metadata->validation_code, _x, 0, 'C'); # CODIGO 
            $pdf->Code128(73, 251, $metadata->invoice_number, 20, 3);
            $pdf->SetXY(64, 254);
            $pdf->cell(37, 4, $metadata->invoice_number, _x, 0, 'C'); # N PLANILLA 
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXy(55, -22);
            $pdf->MultiCell(59, 4, $metadata->firm_name, 0, 'L'); # NOMBRE / RAZON SOCIAL 
            $pdf->SetXY(30, -18);
            $pdf->cell(25, 4, $metadata->rif, _x, 0, 'C'); # CI / RIF 
            $pdf->SetXY(37, -12);
            $pdf->SetFont('Arial', '', 10);
            $pdf->cell(27, 5, date('d/m/Y', strtotime($metadata->emision_date)), _x, 0, 'L'); # FECHA DE EMISION 
            $pdf->SetX(108);
            $pdf->cell(25, 5, "WEB", _x, 0, 'L'); # OPERADOR 
            $pdf->SetX(201);
            $pdf->cell(10, 5, $page . "/$totalPages", _x, 0, 'L'); # TOTAL PAGINAS

        }

        $pdf->Output('PLANILLA.pdf', 'I');
    }

    public function print_invoice_megasoft($control)
    {
        $CI = & get_instance();
        $CI->load->library('fpdf/PDF_Code128');
        $CI->load->model('api_model', 'planillas');
        define('FPDF_FONTPATH', 'application/libraries/fpdf/font');
        $pdf = new PDF_Code128('P', 'mm', 'Letter');
        $pdf->SetMargins(9, 10);
        $pdf->SetAutoPageBreak(true, 0.2);

        $data_payment = $CI->planillas->get_online_payment($control);
        
        define('_x', 1);

        #dd($data_payment);
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetLineWidth(0.5);
        $pdf->SetFillColor(244, 123, 32); #NARANJA RENTAS
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetLineWidth(0.1);

        $pdf->Image('css/img/cabecera_pago.png', 8, 7, 199);

        $pdf->SetY(40);
  
        $pdf->cell(100, 10, utf8_decode($data_payment->titulo), _x, 1, 'C', 1); #TITULO DE TABLA

        $pdf->SetTextColor(0, 0, 0);

        $pdf->cell(50, 10, utf8_decode('Nº de Control'), 'LB',  0, 'C');
        $pdf->cell(50, 10, utf8_decode($data_payment->control), 'LBR', 1, 'C'); #NUMERO DE CONTROL

        $pdf->cell(50, 10, 'Fecha', 'LB',  0, 'C');
        $fecha = ($data_payment->date_compensate) ? $data_payment->date_compensate : $data_payment->created;
        $pdf->cell(50, 10, date('d/m/Y', strtotime($fecha)), 'LBR', 1, 'C'); #FECHA

        $pdf->cell(50, 10, utf8_decode('Nº de Planilla'), 'LB',  0, 'C');
        $pdf->cell(50, 10, utf8_decode($data_payment->factura), 'LBR', 1, 'C'); #INVOICE NUMBER

        $pdf->cell(50, 10, utf8_decode('Monto'), 'LB',  0, 'C');
        $pdf->cell(50, 10, utf8_decode(number_format($data_payment->monto, 2, ',', '.')), 'LBR', 1, 'C'); #MONTO

        $pdf->cell(50, 10, utf8_decode('Nº de Tarjeta'), 'LB',  0, 'C');
        $pdf->cell(50, 10, utf8_decode($data_payment->tarjeta), 'LBR', 1, 'C'); #TARJETA

        $pdf->SetFont('Arial', '', 9);

        $pdf->SetXY(120, 40);
        $pdf->MultiCell(100, 5 , preg_replace('/<[\/]?UT>/', '', $data_payment->voucher), 0, 'L'); #VOUCHER

        $pdf->Output("Recibo de pago {$data_payment->control}.pdf", 'I');
    }

}

