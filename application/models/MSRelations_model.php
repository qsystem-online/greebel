<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class MSRelations_model extends MY_Model {
    public $tableName = "msrelations";
    public $pkey = "RelationId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($RelationId ){
        //$ssql = "select * from " . $this->tableName ." where RelationId = ? and fst_active = 'A'";
        $ssql = "select a.*,b.CountryName,c.ProvinceName,d.DistrictName,e.SubDistrictName from " . $this->tableName . " a 
        left join mscountries b on a.CountryId = b.CountryId 
        left join msprovinces c on a.ProvinceId = c.ProvinceId 
        left join msdistricts d on a.DistrictId = d.DistrictId 
        left join mssubdistricts e on a.SubDistrictId = e.SubDistrictId 
        where a.RelationId = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$RelationId]);
        $rwMSRelations = $qr->row();
        
		$data = [
            "msrelations" => $rwMSRelations
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'RelationName',
            'label' => 'Relation Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'PostalCode',
            'label' => 'Postal Code',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];
        
        $rules[] = [
            'field' => 'NPWP',
            'label' => 'NPWP',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        return $rules;
    }
}