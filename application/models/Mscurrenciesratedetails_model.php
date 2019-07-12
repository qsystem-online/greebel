<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mscurrenciesratedetails_model extends MY_Model {
    public $tableName = "mscurrenciesratedetails";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function deleteByDetail($fst_curr_code){
		$ssql = "delete from " . $this->tableName  . " where fst_curr_code = ?";
		$this->db->query($ssql,[$fst_curr_code]);
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

       $rules[] = [
            'field' => 'fst_curr_code',
            'label' => 'Currencies Code',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] =[
			'field' => 'fdc_exchange_rate_to_idr',
			'label' => 'IDR',
			'rules' => 'required|numeric',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'numeric' => '%s harus berupa angka'
			)
		];

        return $rules;
    }
}