<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class MSDistricts_model extends MY_Model {
    public $tableName = "msdistricts";
    public $pkey = "DistrictId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($DistrictId ){
        $ssql = "select DistrictId,DistrictName from " . $this->tableName ." where DistrictId = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$DistrictId]);
        $rwMSDistricts = $qr->row();
        
		$data = [
            "msdistricts" => $rwMSDistricts
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'DistrictName',
            'label' => 'District Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }
}