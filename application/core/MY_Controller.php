<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $messages;

	public function __construct()
	{
		parent::__construct();
		$this->messages = new MessageSession();
	}

}