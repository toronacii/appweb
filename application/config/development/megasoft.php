<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$url_dev = "https://200.71.151.226:8443/payment/action/paymentgatewayuniversal-";

$config = array(
	'cod_affiliate' => '27112013',
	'credentials'   => base64_encode("alcaldiasucre01:Alcaldia2013-+"),
	'pre_register'  => $url_dev . 'prereg?cod_afiliacion=@cod_affiliate&factura=@invoice_number&monto=@amount',
	'verifier'      => $url_dev . 'querystatus?control=@control',
	'redirect'		=> $url_dev . 'data?control=@control'
);

