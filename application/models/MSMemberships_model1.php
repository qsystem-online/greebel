<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Msmemberships_model extends MY_Model {
    public $tableName = "msmemberships";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($fin_rec_id ){
        $ssql = "select a.*,b.fst_relation_name,c.fst_member_group_name as fst_member_group_name from msmemberships a left join msrelations b on a.fin_relation_id = b.fin_relation_id
        left join msmembergroups c on a.fin_member_group_id = c.fin_member_group_id where a.fin_rec_id = ?";
		$qr = $this->db->query($ssql,[$fin_rec_id]);
        $rwMSMemberships = $qr->row();
        
		$data = [
            "ms_memberships" => $rwMSMemberships
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_member_no',
            'label' => 'Member No',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'fst_name_on_card',
            'label' => 'Name On Card',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'fdt_expiry_date',
            'label' => 'Expiry Date',
            'rules' => array(
				'required'				
			),
            'errors' => array(
				'required' => '%s tidak boleh kosong',
			),
        ];
        
        $rules[] =[
			'field' => 'fdc_member_discount_percent',
			'label' => 'Member Discount',
			'rules' => 'numeric',
			'errors' => array(
				'numeric' => '%s harus berupa angka'
			)
        ];

        return $rules;
    }

    public function get_Memberships(){
        $query = $this->db->get("(select a.*,b.fst_relation_name,c.fst_member_group_name as fst_member_group_name from msmemberships a inner join msrelations b on a.fin_relation_id = b.fin_relation_id
        left join msmembergroups c on a.fin_member_group_id = c.fin_member_group_id) a");
		return $query->result_array();
    }
}