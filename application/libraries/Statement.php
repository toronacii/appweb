<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Statement {
    
    const YEAR_INI = 2010;
    const YEAR_INIT_MONTHLY = 2015;
    const MONTH_INIT_MONTHLY = 1;

    private $CI;
    public $months_names = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    
    function __construct() {
        
        $this->CI = & get_instance();
    }
    
    public function get_select_statement()
    {
        return array_merge($this->_select_monthly(), $this->_select_simple());
    }

    private function _select_simple(){
        $year_now = (int)date('Y');

        for ($year = self::YEAR_INI; $year < self::YEAR_INIT_MONTHLY; $year++)
        {
            $return[] = "SIMPLE_" . $year;
/*
            if ($year + 1 < 2015 && ($year + 1 == $year_now || ($year == $year_now && $month_now >= 10))){
                $return[] = 'FALSE_' . ($year + 1);
            }
*/
        }
        return array_reverse($return);
    }

    private function _select_monthly()
    {
        $year_now = date('Y');
        $month_now = date('m');
        $not_aplicable = [];
        $return = [];

        for ($year = self::YEAR_INIT_MONTHLY; $year <= $year_now; $year++)
        {
            for ($month = self::MONTH_INIT_MONTHLY; $month <= 12; $month++)
            {
                if ($year < $year_now || $month < $month_now)
                    $return[] = "{$month}_{$year}";
            }
        }

        return array_reverse($return);
    }
}
