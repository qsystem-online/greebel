<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mscountries_model extends MY_Model {
    public $tableName = "mscountries";
    public $pkey = "fin_country_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($fin_country_id ){
        $ssql = "select fin_country_id, fst_country_name from " . $this->tableName ." where fin_country_id = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$fin_country_id]);
        $rwMSCountries = $qr->row();
        
		$data = [
            "mscountries" => $rwMSCountries
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_country_name',
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