<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class MSCurrenciesratedetails_model extends MY_Model {
    public $tableName = "mscurrenciesratedetails";
    public $pkey = "recid";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($recid ){
        $ssql = "select * from " . $this->tableName ." where recid = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$recid]);
        $rw = $qr->row();
        
		$data = [
            "" => $rw
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
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
            'field' => 'Date',
            'label' => 'Date',
            'rules' => array(
                'required'),
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        $rules[] =[
			'field' => 'ExchangeRate2IDR',
			'label' => 'IDR',
			'rules' => 'numeric',
			'errors' => array(
				'numeric' => '%s harus berupa angka',
			)
		];

        return $rules;
    }
}