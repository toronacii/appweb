<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation{

	public function __construct()
	{
		parent::__construct();
	}

	public function add_error($field, $error)
	{
		$this->_error_array[$field] = $error;
		$this->_safe_form_data = TRUE;

	}

	/**
	 * Alpha-numeric with underscores and dashes
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function alpha_dash($str)
	{
		return ( ! preg_match("/^([-a-z0-9_-\sñÑ])+$/i", $str)) ? FALSE : TRUE;
	}

}