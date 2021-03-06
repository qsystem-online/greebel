<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trsuratjalandetails_model extends MY_Model {
    public $tableName = "trsuratjalandetails";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fin_trans_detail_id',
            'label' => 'Transaction Detail ID',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        return $rules;
    }
    
    public function deleteByHId($sjId){
        $ssql ="delete from trsuratjalandetails where fin_sj_id = ?";
        $this->db->query($ssql,[$sjId]);

    }

    public function getDataById($recId){
        $ssql = 'select * from trsuratjalandetails where fin_rec_id =?';
        $qr = $this->db->query($ssql,[$recId]);
        $rw = $qr->row();
        return $rw;
    }

}
