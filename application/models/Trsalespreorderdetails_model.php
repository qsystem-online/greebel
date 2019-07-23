<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Trsalespreorderdetails_model extends MY_Model {
    public $tableName = "preorderbranchdetails";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function deleteByDetail($fin_preorder_id){
		$ssql = "delete from " . $this->tableName  . " where fin_preorder_id = ?";
		$this->db->query($ssql,[$fin_preorder_id]);
	}

    public function getRules($mode = "ADD", $id = 0) {
        $rules = [];

        $rules[] = [
            'field' => 'fdb_qty',
            'label' => 'Qty',
            'rules' => 'required|numeric',
			'errors' => array(
                'required' => '%s tidak boleh kosong',
                'numeric' => '%s harus berupa angka',
			)
            ];

        return $rules;
    }

    public function getPreorderDetail($fin_preorder_id){
        $ssql = "select a.*,b.fst_branch_name from ". $this->tableName .  " a left join msbranches b on a.fin_branch_id = b.fin_branch_id  
        where a.fin_preorder_id = ? and a.fst_active = 'A'";        
        $qr = $this->db->query($ssql,[$fin_preorder_id]);
        return $qr->result();
    }
}