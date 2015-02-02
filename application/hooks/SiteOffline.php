<?php

class SiteOffline {
    
   
    function offline() {

        include(APPPATH . 'config/config.php');

        if (defined('IS_OFFLINE') && IS_OFFLINE) {
            
            if (isset($config['offline_allowed_ips']) && ! in_array($_SERVER['REMOTE_ADDR'], $config['offline_allowed_ips'])) {

                $this->mostrar_mantenimiento();
                exit();
            }
        }
    }

    function mostrar_mantenimiento() {
    	include(APPPATH . 'views/offline.php');
    } 
} 