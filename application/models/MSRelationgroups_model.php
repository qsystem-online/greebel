<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Msrelationgroups_model extends MY_Model {
    public $tableName = "msrelationgroups";
    public $pkey = "fin_relation_group_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($fin_relation_group_id ){
        $ssql = "select fin_relation_group_id,fst_relation_group_name from " . $this->tableName ." where fin_relation_group_id = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$fin_relation_group_id]);
        $rwMSRelationgroups = $qr->row();
        
		$data = [
            "msrelationgroups" => $rwMSRelationgroups
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_relation_group_name',
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