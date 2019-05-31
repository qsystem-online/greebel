<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class MSSubdistricts_model extends MY_Model {
    public $tableName = "mssubdistricts";
    public $pkey = "SubDistrictId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($SubDistrictId ){
        $ssql = "select SubDistrictId,SubDistrictName from " . $this->tableName ." where SubDistrictId = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$SubDistrictId]);
        $rwMSSubdistricts = $qr->row();
        
		$data = [
            "mssubdistricts" => $rwMSSubdistricts
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'SubDistrictName',
            'label' => 'Sub District Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }
}