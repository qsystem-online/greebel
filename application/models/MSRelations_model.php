<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class MSRelations_model extends MY_Model {
    public $tableName = "msrelations";
    public $pkey = "RelationId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($RelationId ){
        $ssql = "select * from " . $this->tableName ." where RelationId = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$RelationId]);
        $rw = $qr->row();
        
		$data = [
            "" => $rw
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'RelationType',
            'label' => 'Relation Type',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

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
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }
}