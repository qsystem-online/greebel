<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Trpodetails_model extends MY_Model {
    public $tableName = "trpodetails";
    public $pkey = "fin_po_detail_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fdb_qty',
            'label' => 'Purchase Order Qty',
            'rules' => 'required|numeric',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
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
    public function deleteByPOId($fin_po_id){
        $this->db->delete('trpodetails', array('fin_po_id' => $fin_po_id)); 
    }

    public function getQtyPLB($fin_po_detail_id){
        return 0;
    }

    public function getDataById($finPODetailId){
        $ssql = "select * from trpodetails where fin_po_detail_id = ?";
        $qr = $this->db->query($ssql,[$finPODetailId]);
        return $qr->row();
    }
    
}


