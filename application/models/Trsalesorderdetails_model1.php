<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Trsalesorderdetails_model extends MY_Model {
    public $tableName = "trsalesorderdetails";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function deleteByDetail($fin_salesorder_id){
		$ssql = "delete from " . $this->tableName  . " where fin_salesorder_id = ?";
		$this->db->query($ssql,[$fin_salesorder_id]);
	}

    public function getRules($mode = "ADD", $id = 0) {
        $rules = [];

        $rules[] = [
            'field' => 'fdc_qty',
            'label' => 'Qty',
            'rules' => 'required|numeric',
			'errors' => array(
                'required' => '%s tidak boleh kosong',
                'numeric' => '%s harus berupa angka',
			)
            ];

        $rules[] = [
            'field' => 'fdc_price',
            'label' => 'Price',
            'rules' => 'required|numeric',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'numeric' => '%s harus berupa angka',
            )
        ];

        return $rules;
    }

    public function getSoDetail($fin_salesorder_id){
        $ssql = "select a.*,b.fst_item_name,c.fst_item_discount from ". $this->tableName .  " a left join msitems b on a.fin_item_id = b.fin_item_id 
        left join msitemdiscounts c on a.fst_disc_item = c.fst_item_discount 
        where a.fin_salesorder_id = ? and a.fst_active = 'A'";        
        $qr = $this->db->query($ssql,[$fin_salesorder_id]);
        return $qr->result();
    }
}