<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class MSProvinces_model extends MY_Model {
    public $tableName = "msprovinces";
    public $pkey = "ProvinceId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($ProvinceId ){
        $ssql = "select ProvinceId,ProvinceName from " . $this->tableName ." where ProvinceId = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$ProvinceId]);
        $rwMSProvinces = $qr->row();
        
		$data = [
            "msprovinces" => $rwMSProvinces
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'ProvinceName',
            'label' => 'Province Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }
}