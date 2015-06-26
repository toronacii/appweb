<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MessageSession {
    
    private $CI;
    public $types;
    
    function __construct() {
        
        $this->CI = & get_instance();
        $this->types = (object)array(
            'danger' => 'danger',
            'info' => 'info',
            'warning' => 'warning',
            'success' => 'success'
        );
        if (!$this->CI->session){
            
            $this->CI->load->library("session");
    
        }
    }
    
    function add_message($message, $type = "danger"){
        if (! $this->CI->session->userdata(base_url('messages')))
            $this->CI->session->set_userdata(base_url('messages'),array());
        
        $messages = $this->CI->session->userdata(base_url('messages'));
        $messages[$type][] = $message;
        
        $this->CI->session->set_userdata(base_url('messages'),$messages);
    }
    
    function get_messages($show = false){
        $html = "";
        if ($messages = $this->CI->session->userdata(base_url('messages'))){
            
            foreach ($messages as $type => $typeMessage)
            {
                foreach ($typeMessage as $message)
                {
                    $html .= "<div class='alert alert-{$this->types->$type} flash'>$message</div>";
                }
            }
            
            $this->CI->session->unset_userdata(base_url('messages'));
        }
        if ($show) echo $html;
        else return $html;

        #var_dump($html);
    }
    
    function have_errors(){
        if ($messages = $this->CI->session->userdata(base_url('messages')))
            if (isset($messages['danger']) && count($messages['danger']) > 0) 
                return true;
        return false;
    }
    
}
