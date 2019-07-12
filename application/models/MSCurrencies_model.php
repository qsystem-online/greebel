<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MSCurrencies_model extends MY_Model{
    public $tableName = "mscurrencies";
    public $pkey = "CurrCode";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($CurrCode){
        $ssql = "SELECT CurrCode,CurrName FROM mscurrencies where CurrCode = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$CurrCode]);
        $rwCurrency = $qr->row();

        $ssql = "SELECT a.*,b.CurrCode,Date,ExchangeRate2IDR FROM mscurrencies a LEFT JOIN mscurrenciesratedetails b ON a.CurrCode = b.CurrCode WHERE a.CurrCode = ? and a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$CurrCode]);
        $rsCurrDetails = $qr->result();

        $data = [
            "msCurrency" => $rwCurrency,
            "msCurrDetails" => $rsCurrDetails
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'CurrCode',
            'label' => 'Currencies Code',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] = [
            'field' => 'CurrName',
            'label' => 'Currencies Name',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        return $rules;
    }

    
}
