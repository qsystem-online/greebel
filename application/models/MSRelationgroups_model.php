<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class MSRelationgroups_model extends MY_Model {
    public $tableName = "msrelationgroups";
    public $pkey = "RelationGroupId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($RelationGroupId ){
        $ssql = "select RelationGroupId,RelationGroupName from " . $this->tableName ." where RelationGroupId = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$RelationGroupId]);
        $rwMSRelationgroups = $qr->row();
        
		$data = [
            "msrelationgroups" => $rwMSRelationgroups
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'RelationGroupName',
            'label' => 'Relation Group Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }

    public function get_RelationGroups(){
        $query = $this->db->get('msrelationgroups');
		return $query->result_array();
    }
}