<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Msproductiontype_model extends MY_Model{
    public $tableName ="msproductiontype";
    public $pkey ="fin_rec_id";

    public function __construct(){
        parent::__construct();
    }

    public function getDataById($id){
        $ssql = "SELECT * FROM msproductiontype where fin_rec_id = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql, [$id]);
        $rw = $qr->row();

        $data = [
            "msproductiontype" => $rw
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_production_type',
            'label' => 'Type name',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];

        return $rules;
    }
    



}