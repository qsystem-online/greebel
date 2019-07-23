<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mscurrencies_model extends MY_Model{
    public $tableName = "mscurrencies";
    public $pkey = "fst_curr_code";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fst_curr_code){
        $ssql = "SELECT fst_curr_code,fst_curr_name FROM mscurrencies where fst_curr_code = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fst_curr_code]);
        $rwCurrency = $qr->row();

        $ssql = "SELECT a.*,b.fst_curr_code,fdt_date,fdc_exchange_rate_to_idr FROM mscurrencies a LEFT JOIN mscurrenciesratedetails b ON a.fst_curr_code = b.fst_curr_code WHERE a.fst_curr_code = ? and a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fst_curr_code]);
        $rsCurrDetails = $qr->result();

        $data = [
            "ms_Currency" => $rwCurrency,
            "ms_CurrDetails" => $rsCurrDetails
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_curr_code',
            'label' => 'Currencies Code',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'fst_curr_name',
            'label' => 'Currencies Name',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        return $rules;
    }

    
}
