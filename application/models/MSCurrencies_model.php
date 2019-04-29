<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class MSCurrencies_model extends MY_Model {
    public $tableName = "mscurrencies";
    public $pkey = "CurrCode";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($CurrCode ){
        $ssql = "select * from " . $this->tableName ." where CurrCode = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$CurrCode]);
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
            'field' => 'CurrName',
            'label' => 'Currencies Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }
}