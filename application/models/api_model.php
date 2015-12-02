<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_model extends CI_Model {

    private $controller;
    public  $last_curl;
    public  $errors;
    public $args;

    public function __construct($controller)
    {
        $this->controller = $controller;
    }

    
    /*
    public function __call($method, $args)
    {      

        $args = ($args) ? implode('/', $args) : '';

        $this->last_curl = API_DIR . "/{$this->controller}/$method/$args";

        $response = json_decode($this->curl->simple_get($this->last_curl)); 

        #var_dump(API_DIR . "/{$this->controller}/$method/$args", $this->curl->info) ;

        #$this->last_curl = $this->curl->info['url'];

        if (isset($response->php_error) || $this->curl->error_code)
        {
            if (isset($response->php_error))
            {
                $this->errors = $response->php_error;
            }
            else if ($this->curl->error_code)
            {
                $this->curl->info['error_string'] = $this->curl->error_string;
                $this->curl->info['error_code'] = $this->curl->error_code;
                $this->errors = $this->curl->info;
            }
            return NULL;
        }

        #var_dump($this->curl->error_code, $this->curl->error_string, $this->curl->info);

        return $response;

    }
    */

    public function __call($method, $args)
    {      
        $this->last_curl = API_DIR . "/{$this->controller}/$method";

        $this->args = $args;

        if (ENVIRONMENT === 'development') 
        {
            $this->last_curl .= "?XDEBUG_SESSION_START=" . API_DEBUG;
            $this->curl->option("TIMEOUT", 30000);
        }

        $response = json_decode($this->curl->simple_post($this->last_curl, $this->args)); 

        if (isset($response->php_error) || $this->curl->error_code)
        {
            if (isset($response->php_error))
            {
                $this->errors = $response->php_error;
            }
            else if ($this->curl->error_code)
            {
                $this->curl->info['error_string'] = $this->curl->error_string;
                $this->curl->info['error_code'] = $this->curl->error_code;
                $this->errors = $this->curl->info;
            }
            return NULL;
        }

        return $response;

    }

}

