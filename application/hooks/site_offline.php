<?php

class Site_offline {
    function offline() {
        //Se incluye el archivo config.php
        include(APPPATH . 'config/config.php');
        //Se verifica que se haya creado el índice $config['is_offline']
        //y que este configurado a TRUE
        if (isset($config['is_offline']) && $config['is_offline'] === TRUE) {
            //Ahora se verifica que se haya creado el índice $config['offline_allowed_ips']
            //y que la IP del visitante $_SERVER['REMOTE_ADDR'] no pertenezca a las IP's
            //especificadas en $config['offline_allowed_ips'] por ellos se compara que
            //sea FALSE
            if (isset($config['offline_allowed_ips']) && in_array($_SERVER['REMOTE_ADDR'], $config['offline_allowed_ips']) === FALSE) {
                 //Si todo es correcto se llama al método que mostrará el mensaje
                $this->mostrar_mantenimiento();
                exit(); //Se detiene la ejecución de los demás elementos del sitio
            }
        }
    }
    //Muestra un mensaje sencillo el cual puede contener cualquier código HTML
    //para que el mensaje de mantenimiento sea más atractivo
    function mostrar_mantenimiento() {
        echo '<html><body><h2>Este sitio web se encuentra en mantenimiento</h2></body></html>';
    } 
}