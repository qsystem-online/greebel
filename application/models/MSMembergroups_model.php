<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class MSMembergroups_model extends MY_Model {
    public $tableName = "msmembergroups";
    public $pkey = "fin_member_group_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($fin_member_group_id){
        $ssql ="SELECT * FROM msmembergroups WHERE fin_member_group_id = ? ORDER BY fin_member_group_id";
        $qr = $this->db->query($ssql, [$fin_member_group_id]);
        $rwMemberGroups = $qr->row();

        $data = [
            "ms_membergroups" => $rwMemberGroups
		];

		return $data;
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_member_group_name',
            'label' => 'Member Group Name',
            'rules' => 'required|min_length[3]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 3 character'
            )
        ];

        return $rules;
    }
}