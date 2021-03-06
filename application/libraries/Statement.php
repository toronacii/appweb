<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Statement {
    
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
            'month' => is_numeric($sttm['sttm'][0]) ? $sttm['sttm'][0] : 'NULL',
            'closing' => (isset($sttm['sttm'][2]) && $sttm['sttm'][2] === 'CLOSING')
        ];
    }
    
    public function get_select_statement()
    {
        return new SelectStatement();
    }

    public function get_month($month)
    {
        return $this->months_names[$month - 1];
    }

    public function order_errors_declare_taxpayer_monthly($param)
    {

        $errors = $this->CI->declaraciones->get_errors_declare_monthly($this->CI->id_taxpayer, $param->year, $param->month, $param->type);

        $final = [];
        $return = [];

        if ($errors) 
        {
            foreach ($errors as $obj) {
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

            if ($value != 0 && empty($value))
            {
                $value = "null";
            }

            $return .= "{" . ((!$index) ?: "$key, ") . "$value". "}, ";
        }
        return substr($return, 0, -2) . "}";
    }

    public function to_array_pgsql($obj)
    {
        if (is_object($obj)) $obj = (array)$obj;
        if (is_array($obj) && count($obj) == 0) return null;

        $return = [];

        foreach($obj as $id1 => $element1)
        {
            foreach ($element1 as $id2 => $element2)
            {
                $return[] = "{" . "{$id1},{$id2},{$element2}" . "}";
            }
        }

        return "{" . implode(",", $return) . "}";
    }

    public function my_format_number($number, $toEnglish = true)
    {
        if ($toEnglish)
        {
            return str_replace(',','.',str_replace('.', '', $number));
        }

        return number_format($number, 2, ',', '.');
    }

    

    public function get_init_vars($data_planilla)
    {
        $r = new StdClass();

        $r->fiscal_year = $data_planilla[0]->fiscal_year;
        $r->month = $data_planilla[0]->month;
        $r->is_monthly = $r->month > 0;
        $r->subtitulo = "DETERMINACIÓN DE IMPUESTO AÑO {$r->fiscal_year}";
        $r->month_text = "AÑO";
        $r->periodo_declarado = (object)[
            'init_month' => '01',
            'last_month' => '12',
            'last_day' => '31'
        ];
        $r->is_closing = $data_planilla[0]->closing;

        return (array)$r;
    }

    public function proccess_array($array)
    {
        $return = [];
        foreach ($array as $id => $arr) {
            $return[$id] = array_keys($arr)[0];
        }

        return $return;
    }

    public function save_statement_closing($id_tax, $fiscal_year, $type, $month, $statements = null) {

        $id_sttm_form = $this->CI->declaraciones->save_statement_closing(
            $id_tax, 
            $fiscal_year, 
            $type, 
            $month,  
            $statements);

        switch ($id_sttm_form) 
        {
            case -2: throw new Exception("Debe ser una declaración de cierre", $id_sttm_form);
            case -1: throw new Exception("No cumple con los requisitos para realizar esta declaración", $id_sttm_form);
            case 0 : throw new Exception("Error al guardar declaracion, intente de nuevo mas tarde", $id_sttm_form);
        }

        return $id_sttm_form;
    }

    public function liquid_statement_closing($id_statement_form)
    {
        $liquid = $this->CI->declaraciones->liquid_statement_closing($id_statement_form);

        switch ($liquid)
        {
            case -3:
            case 0 : throw new Exception("Error al realizar declaracion, intente de nuevo mas tarde", $liquid);
            case -2: throw new Exception("Debe ser una declaración de cierre", $liquid);
            case -1: throw new Exception("No cumple con los requisitos para realizar esta declaración", $liquid);
        }

        return $liquid;
    }

}

class StatementOption {

    const CLOSING_YEAR = 'YCLOSING';
    const CLOSING_MONTH = 'MCLOSING';
    const YEARLY = 'YEARLY';
    const MONTHLY = 'MONTHLY';

    private $MONTH_NAMES = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    public $type;
    public $month;
    public $year;
    public $closing;

    public function __construct($string)
    {
        $params = explode("_", $string);

        $this->type = $params[0];
        $this->month = (int)$params[1];
        $this->year = $params[2];

        $this->closing = !!preg_match('/closing/i', $this->type);
    }

    public function toString()
    {
        return "{$this->type}_{$this->month}_{$this->year}";
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function get_title($short = false)
    {
        $title = "";

        switch ($this->type)
        {
            case self::YEARLY :

                $title = ($short) ? 'Definitiva' : 'Declaracion definitiva de ingresos brutos';

                break;

            case self::MONTHLY :

                $title = (($short) ? 'DJM ' : 'Declaracion jurada mensual de ingresos brutos ') . $this->get_month_name($this->month);

                break;

            case self::CLOSING_MONTH :

                $title = (($short) ? 'DJC ' : 'Declaracion jurada de cese de actividades ') . $this->get_month_name($this->month);

                break;

            case self::CLOSING_YEAR :

                $title = ($short) ? 'DJAC ' : 'Declaracion jurada de cierre anual ';

                break;
        }

        return "{$title} {$this->year}";
    }

    public function show_step_specified_activities()
    {
        return $this->year >= 2013 && $this->type === self::YEARLY;
    }

    private function get_month_name($month)
    {
        return $this->MONTH_NAMES[$month - 1];
    }

}

class SelectStatement {
    
    public $present;

    public $previous;

    public $special;

    public function __construct() 
    {
        $months = $this->select_monthly();
        $simple = $this->select_simple();

        $this->present = array_shift($months);

        $this->previous = (object)[
            "optgroup" => "Declaraciones Anteriores",
            "options" => array_merge($months, $simple)
        ];

        $this->special = (object)[
            "optgroup" => "Declaraciones Especiales",
            "options" => $this->select_special()
        ];
    }

    private function select_monthly()
    {
        $end = new DateTime('-1 month');
        $begin = new DateTime();
        $return = [];

        for ($begin->setDate(Statement::YEAR_INIT_MONTHLY, Statement::MONTH_INIT_MONTHLY, 1); $begin < $end; $begin->modify('+1 month'))
        {
            $return[] = new StatementOption(StatementOption::MONTHLY . $begin->format("_m_Y"));
        }

        return array_reverse($return);
    }

    private function select_simple()
    {
        $return = [];

        for ($year = Statement::YEAR_INI; $year < Statement::YEAR_INIT_MONTHLY; $year++)
        {
            $return[] = new StatementOption(StatementOption::YEARLY . "_0_" . $year);
        }

        return array_reverse($return);
    }

    private function select_special()
    {
        $today = new DateTime();

        $return = [];
        $return[] = new StatementOption(StatementOption::MONTHLY . $today->format('_m_Y'));
        $return[] = new StatementOption(StatementOption::CLOSING_MONTH . $today->format('_m_Y'));

        if ($today->format('Y') > Statement::YEAR_INIT_MONTHLY) 
        {
            $return[] = new StatementOption(StatementOption::CLOSING_YEAR . "_0_" . ((int)$today->format('Y') - 1));
        }

        return $return;
    }

}


