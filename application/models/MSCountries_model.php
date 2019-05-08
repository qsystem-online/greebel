<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class MSCountries_model extends MY_Model {
    public $tableName = "mscountries";
    public $pkey = "CountryId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($CountryId ){
        $ssql = "select CountryId, CountryName from " . $this->tableName ." where CountryId = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$CountryId]);
        $rwMSCountries = $qr->row();
        
		$data = [
            "mscountries" => $rwMSCountries
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'CountryName',
            'label' => 'Country Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }
}