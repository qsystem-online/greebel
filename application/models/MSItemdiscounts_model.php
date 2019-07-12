<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Msitemdiscounts_model extends MY_Model {
    public $tableName = "msitemdiscounts";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($fin_rec_id ){
        $ssql = "select * from " . $this->tableName ." where fin_rec_id = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$fin_rec_id]);
        $rwDiscounts = $qr->row();
        
		$data = [
            "msDiscounts" => $rwDiscounts
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_item_discount',
            'label' => 'Item Discount',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        return $rules;
    }
}