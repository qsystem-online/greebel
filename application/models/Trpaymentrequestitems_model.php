<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Trpaymentrequestitems_model extends MY_Model {
    public $tableName = "trpaymentrequestitems";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];
        $rules[] = [
            'field' => 'fst_notes',
            'label' => 'Keterangan',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];  
        return $rules;
    }

    public function deleteById($fin_paymentrequest_id){
        $this->db->delete('trpaymentrequestitems', array('fin_paymentrequest_id' => $fin_paymentrequest_id)); 
    }


    public function getDataById($fin_paymentrequest_id){
        $ssql = "select * from trpaymentrequestitems where fin_rec_id = ?";
        $qr = $this->db->query($ssql,[$fin_paymentrequest_id]);
        return $qr->row();
    }
}