<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Statement_retirement_month {
    
    const YEAR_INI = 2009;
    const YEAR_INIT_MONTHLY = 2015;
    const MONTH_INIT_MONTHLY = 1;

    private $CI;
    private $months_names = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    
    function __construct() {
        
        $this->CI = & get_instance();
    }

    public function get_sttm_properties($sttm){
        
        return (object)[
            'type' => is_numeric($sttm['sttm'][0]) ? 'TRUE' : $sttm['sttm'][0],
            'fiscal_year' => $sttm['sttm'][1],
            'month' => is_numeric($sttm['sttm'][0]) ? $sttm['sttm'][0] : 'NULL'
        ];
    }
    
    public function get_select_statement()
    {
        return array_merge($this->_select_monthly(), $this->_select_simple());
    }

    public function get_month($number)
    {
        return $this->months_names[$number];
    }

    public function get_title_statement($data_sttm, $short = false)
    {
        $year = $data_sttm[1];
        $type_month = $data_sttm[0];

        if ($type_month == 'TRUE')
        {
            $title = ($short) ? 'Definitiva' : 'Declaracion definitiva de ingresos brutos';
        }
        else
        {
            $title = (($short) ? 'DJM ' : 'Declaracion jurada mensual de ingresos brutos ') . $this->get_month($type_month);
        }

        return $title . " " . $year;
    }

    private function _select_simple(){
        $year_now = (int)date('Y');

        for ($year = self::YEAR_INI; $year < self::YEAR_INIT_MONTHLY; $year++)
        {
            $return[] = "TRUE_" . $year;
/*
            if ($year + 1 < 2015 && ($year + 1 == $year_now || ($year == $year_now && $month_now >= 10))){
                $return[] = 'FALSE_' . ($year + 1);
            }
*/
        }
        return array_reverse($return);
    }

    public function order_errors_declare_taxpayer_monthly($r)
    {
        $final = array();
        if ($r) 
        {
            foreach ($r as $obj) {
                $return[$obj->tax_account_number][] = $obj;
            }
            foreach ($return as $tan => $array) {
                foreach ($array as $obj) {
                    $index = $tan . "_" . $obj->id_tax . "_" . $obj->id_sttm_form;
                    if (! isset($final[$index]))
                        $final[$index] = array();
                    $id_message = ($obj->id_message === NULL) ? -1 : $obj->id_message;
                    if (in_array($id_message, array(0, 1))) { # inconsistencia, existe declaracion
                        unset($final[$index]);
                        break;
                    } else if ($obj->message) {
                        $final[$index][] = $obj->message;
                    }
                }
            }
        }

        return $final;
    }

    public function show_step_specified_activities($sttm)
    {
        $year = $sttm[1];
        $type = $sttm[0];

        if ($year >= 2013 && $type == 'TRUE')
        {
            return true;
        }

        return false;
    }

    public function to_array_pgsql_data($obj, $index = true)
    {
        $return = '{';
        foreach ($obj as $key => $value) {
            
            if (is_array($value) || is_object($value))
            {
                $value = $this->to_array_pgsql_data($value, $index);
            }

            $value = trim($value);

            if (preg_match_all('/[\d+\.?]+,\d+/', $value))
            {
                $value = $this->my_format_number($value);
            }

            if (empty($value))
            {
                $value = "null";
            }

            $return .= "{" . ((!$index) ?: "$key, ") . "$value". "}, ";
        }
        return substr($return, 0, -2) . "}";
    }

    public function my_format_number($number, $toEnglish = true)
    {
        if ($toEnglish)
        {
            return str_replace(',','.',str_replace('.', '', $number));
        }

        return number_format($number, 2, ',', '.');
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

    public function get_init_vars($data_planilla)
    {
        $r = new StdClass();

        $r->fiscal_year = $data_planilla[0]->fiscal_year;
        $r->month = $data_planilla[0]->month;
        $r->is_monthly = is_numeric($r->month);
        $r->subtitulo = "DETERMINACIÓN DE IMPUESTO AÑO {$r->fiscal_year}";
        $r->month_text = "AÑO";
        $r->periodo_declarado = (object)[
            'init_month' => '01',
            'last_month' => '12',
            'last_day' => '31'
        ];

        return (array)$r;
    }

}
