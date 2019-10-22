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

    public function getArrRate($date=""){
        $ssql ="select * from mscurrencies where fst_active ='A'";
        $qr = $this->db->query($ssql,[]);
        $rs = $qr->result();
        $result = [];
        foreach($rs as $rw){
            $result[$rw->fst_curr_code] = $this->getRate($rw->fst_curr_code,$date);            
        }
        return $result;

    }

    public function getRate($fst_curr_code,$date=""){
        /*
        $ssql = "select * from mscurrencies where fst_curr_code = ?";
        $qr = $this->db->query($ssql,[$fst_curr_code]);
        $rw = $qr->row();
        if($rw->)
        */
        if ($fst_curr_code == "IDR"){
            return 1;
        }
        $date = $date =="" ? date("Y-m-d") : $date;

        $ssql ="select * from mscurrenciesratedetails where fst_curr_code = ? and fdt_date = ? ";
        $qr = $this->db->query($ssql,[$fst_curr_code,$date]);
        $rw = $qr->row();
        if(!$rw){
            return 0;
        }else{
            return $rw->fdc_exchange_rate_to_idr;
        }
        return $date; 

    }

    public function getDefaultCurrencyCode(){
        return $this->db->query("select * from mscurrencies where fbl_is_default= 1").row()->fst_curr_code;
    }

    
}
