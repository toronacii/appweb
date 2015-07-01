<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment {

	private $config;
	private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->config('megasoft', TRUE);
		$this->config = (object)$this->CI->config->config['megasoft'];

		$this->CI->load->model('api_model', 'pago_online');
	}

	private function true_url($url, $params)
	{
		foreach ($params as $key => $value) {
			$url = str_replace("@$key", $value, $url);
		}

		return $url;
	}

	private function curl_external($url)
	{
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/xml', "Authorization: Basic {$this->config->credentials}"));
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$resp = curl_exec($ch);
		curl_close($ch);

		return $resp;
	}

	private function xml_to_object($xml)
	{
		$object = simplexml_load_string($xml);
		$object->descripcion = (string)$object->descripcion;
		$object->voucher = (string)$object->voucher;
		
		$object = (array) $object;

		foreach ($object as $i => $v)
		{
			$v = (is_object($v)) ? (array) $v : $v;
			if (is_array($v) && empty($v))
			{
				$object[$i] = null;
			}
		}
		
		return (object)$object;
	}

	private function get_control_code($invoice_number, $amount)
	{
		$params = array(
			'cod_affiliate'  => $this->config->cod_affiliate,
			'invoice_number' => $invoice_number,
			'amount' => number_format($amount, 2, '.', '')
		);
		$url = $this->true_url($this->config->pre_register, $params);

		return $this->curl_external($url);
	}

	private function get_data_verifier($control)
	{
		$params['control'] = $control;
		$url = $this->true_url($this->config->verifier, $params);

		$data = $this->curl_external($url);

		return $this->xml_to_object($data);
	}

	public function set_control_number($id_invoice, $url_origin)
	{
		$invoice = $this->CI->pago_online->get_data_invoice($id_invoice);

        #dd($invoice, $this->pago_online);

        #PLANILLA NO EXISTE
        if (! $invoice)
        {
            throw new Exception('No existe esa planilla');
        }

        #PLANILLA YA CANCELADA
        if ($invoice->estado == 'A')
        {
        	throw new Exception('La planilla ya fué pagada');
        }

        #PLANILLA VENCIDA
        if ($invoice->expired == 't')
        {
        	throw new Exception('La planilla está vencida');
        }
        #OBTENER NUMERO DE CONTROL
        $control_number = $this->get_control_code($invoice->invoice_number, $invoice->amount);

        if ($control_number) 
        {
        	$verifier = $this->get_data_verifier($control_number);
	        $verifier->monto = substr($verifier->monto, 0, -2) . "." . substr($verifier->monto, -2);
	        $verifier->id_invoice = $id_invoice;
	        $verifier->pagina = $url_origin;
	        $verifier->validation_code = $invoice->validation_code;
	        $verifier->correo = $invoice->email;

	        $this->CI->pago_online->set_online_payment($verifier);

	        return $control_number;
        }
        else
        {
        	throw new Exception("Ha ocurrido un error");    	
        }
	}

	public function compensate($payment)
	{
		$verifier = $this->get_data_verifier($payment->control);

		if ($payment->estado !== $verifier->estado && $verifier->estado === 'A')
		{
            $result = $this->CI->pago_online->invoice_compensate($payment->id_invoice);

            switch ($result)
            {
            	case -2: throw new Exception("Esta planilla ya fué pagada anteriormente");
            	case -1: throw new Exception("Planilla inexistente, o ya fué pagada");
            	case  0: throw new Exception("Ha ocurrido un error, intente mas tarde");
            }

            $this->CI->load->model('api_model', 'gestion_usuario');

            unset($verifier->monto);
            $payment = $this->CI->pago_online->update_online_payment($verifier);

            $data = (array)$payment + array(
                'email' => $payment->correo,
                'view'  => 'emails.online_payment'
            );

            $respEmail = $this->CI->gestion_usuario->send_email_WS($data);
		}
		else
		{
			switch ($verifier->estado) {
				case 'A': throw new Exception("Esta planilla ya había sido pagada");
				case 'P': throw new Exception("Esta planilla está pendiente por pagar");
				case 'R': throw new Exception("El pago de esta planilla fué rechazado");
			}
		}

		return substr($payment->pagina, 0, strpos($payment->pagina, "index.php")) . "index.php/planillas_pago/show_invoice_megasoft/{$payment->control}";
	}
}