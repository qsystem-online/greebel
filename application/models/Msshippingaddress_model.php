<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Msshippingaddress_model extends MY_Model {
    public $tableName = "msshippingaddress";
    public $pkey = "fin_shipping_address_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($fin_shipping_address_id)
    {
        $ssql = "select * from " . $this->tableName . " where fin_shipping_address_id = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_shipping_address_id]);
        $rw = $qr->row();

        $data = [
            "shipping_Detail" => $rw
        ];

        return $data;
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_name',
            'label' => 'Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }

    public function deleteByHeaderId($fin_relation_id)
    {
        $ssql = "delete from " . $this->tableName . " where fin_relation_id = $fin_relation_id";
        $this->db->query($ssql);
    }

}