<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$url_pro = "https://payment.megasoft.com.ve/payment/action/paymentgatewayuniversal-";

$config = array(

	'cod_affiliate' => '1214449',
	'credentials'   => base64_encode('alcaldiasucre01:Alcaldia2015-+'),
	'pre_register'  => $url_pro . 'prereg?cod_afiliacion=@cod_affiliate&factura=@invoice_number&monto=@amount',
	'verifier'      => $url_pro . 'querystatus?control=@control',
	'redirect'		=> $url_pro . 'data?control=@control'

);
