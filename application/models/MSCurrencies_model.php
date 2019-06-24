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
        $ssql = "SELECT CurrCode,CurrName FROM mscurrencies Where CurrCode = ? and fst_active = 'A' ";
        $qr = $this->db->query($ssql,[$CurrCode]);
        $rwCurrencies = $qr->row();

        $ssql = "SELECT a.*,b.CurrCode FROM mscurrencies a LEFT JOIN mscurrenciesratedetails b ON a.CurrCode = b.CurrCode WHERE a.CurrCode = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$CurrCode]);
        $rsCurrencies = $qr->result();

        $data = [
            "ms_Currencies" => $rwCurrencies,
            "ms_CurrenciesD" => $rsCurrencies
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

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
